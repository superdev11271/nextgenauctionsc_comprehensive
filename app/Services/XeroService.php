<?php

/// app/Services/XeroService.php
namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Models\XeroWebHook;
use DateTime;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


// API referance
// https://developer.xero.com/documentation/api/accounting/invoices/#post-invoices
class XeroService
{
    private function refreshAccessToken()
    {
        $urlAccessToken = 'https://identity.xero.com/connect/token';
        $encoded_header = config('xero.encoded_header');
        $scope = config('xero.scopes');
        try {
            $res = Http::retry(3, 10)
                ->withHeaders(["Authorization" => "Basic $encoded_header",])
                ->asForm()
                ->post($urlAccessToken, [
                    'grant_type' => 'client_credentials',
                    'scope' => $scope,
                ])->json();
            return $res["access_token"];
        } catch (\Exception $e) {
            Log::channel("xerowebhook")->error($e->getMessage());
            exit($e->getMessage());
        }
    }

    private function getAccessToken()
    {
        return Cache::remember('xero_access_token', 1740, function () {
            return XeroService::refreshAccessToken();
        });
    }

    public function createInvoice(User $user, array $products): ?array
    {
        $lineItems = $this->addLineItems($products);

        $invoiceCreateUrl = "https://api.xero.com/api.xro/2.0/Invoices";

        $currentDateTime = (new DateTime('now'))->format('Y-m-d');

        $shipping_address = $user->addresses->where("address_type", 1)->first();
        $billing_address = $user->addresses->where("address_type", 2)->first();

        $payload = [
            "Type" => "ACCREC",
            "Contact" => [
                "Name" => "NG_Auction: $user->name",
                "EmailAddress" => "$user->email",
                "Addresses" =>  [
                    [
                        // Shipping/Delevery Address
                        "AddressType" => "STREET",
                        "AddressLine1" => $shipping_address?->address,
                        "City" =>  $shipping_address?->city?->name,
                        "Region" =>  $shipping_address?->state?->name,
                        "PostalCode" => $user?->postal_code,
                        "Country" =>  $shipping_address?->country?->name,
                    ],
                    [
                        // Billing Address
                        "AddressType" => "POBOX",
                        "AddressLine1" => $billing_address?->address,
                        "City" => $billing_address?->city?->name,
                        "Region" => $billing_address?->state?->name,
                        "PostalCode" => $user?->postal_code,
                        "Country" => $billing_address?->country?->name,
                    ]
                ],
                "Phones" => [
                    [
                        "PhoneType" => "MOBILE",
                        "PhoneNumber" => "$user->phone",
                    ]
                ],
            ],

            // "Reference" => "RPT-DD",
            "Date" => "$currentDateTime",
            "DueDate" => "$currentDateTime",
            "LineAmountTypes" => "Inclusive",
            "LineItems" => $lineItems,
            "Status" => "AUTHORISED"
        ];

        $res = XeroService::dispatchXeroRequest($invoiceCreateUrl, $payload);
        if ($res) {
            return $res->json();
        }
        Log::channel("xerowebhook")->error($res);
        return null;
    }

    public function deleteInvoice($invoiceNumber, $setStatus = "VOIDED")
    {
        // DELETE works if invoice's status is SUBMITTED
        // $invoiceNumber Example: INV-123
        $invoiceCreateUrl = "https://api.xero.com/api.xro/2.0/Invoices/$invoiceNumber";
        $payload = [
            "InvoiceNumber" => $invoiceNumber,
            "Status" => $setStatus
        ];


        $res = XeroService::dispatchXeroRequest($invoiceCreateUrl, $payload);

        if ($res?->ok()) {
            return $res->json()["Invoices"][0]["InvoiceID"];
        } else {
            Log::channel("xerowebhook")->error($res);
            return false;
        }
    }
    public function mailXeroInvoice($invoiceId)
    {
        // $invoiceId Example: aa682059-c8ec-44b9-bc7f-344c94e1ffae
         $url = "https://api.xero.com/api.xro/2.0/Invoices/$invoiceId/Email";
        $res = XeroService::dispatchXeroRequest($url);
        return ($res->status() == 204)?true:false;
    }
    public function fetchInvoice($invoiceId)
    {
        $xeroWebhookNotification = XeroWebHook::find($invoiceId);
        $res = XeroService::dispatchXeroRequest($xeroWebhookNotification->resource_url, method:"get");
        if ($res->ok()) {
            $xeroWebhookNotification->data = $res->json();
            $xeroWebhookNotification->save();
            return $res->json();
        }
        Log::channel("xerowebhook")->error($res);
        throw new \Exception('Bad responce from Xero.');
    }

    public function updateInvoiceStatus($invoiceNumber, $amount)
    {
        // InvoiceNumber Example: INV-123
        $invoiceCreateUrl = "https://api.xero.com/api.xro/2.0/Payments";
        $currentDateTime = (new DateTime('now'))->format('Y-m-d');

        $payload = [
            "Invoice" => ["InvoiceNumber" => "$invoiceNumber"],
            "Account" => ["Code" => "855"],
            "Date" => "$currentDateTime",
            "Amount" => $amount
            // "Reference" => "Full refund as the customer cancelled their subscription"
        ];

        $res = XeroService::dispatchXeroRequest($invoiceCreateUrl, $payload);

        if ($res?->ok()) {
            return true;
        } else {
            Log::channel("xerowebhook")->error($res);
            return false;
        }
    }

    private function addLineItems(array $products)
    {
        $lineItems = [];
        foreach ($products as  $product) {
            $lineItems[] = [
                "Description" => "$product->name",
                "Quantity" => "1",
                "UnitAmount" => $product->getHighestBid()->amount,
                "AccountCode" => "200",
                "DiscountRate" => "0"
            ];
        }
        return $lineItems;
    }

    private function dispatchXeroRequest($endpoint, $payload = [], $method = "post", $headers = [])
    {
        try {
            $headers = array_merge($headers, ["Authorization" => "Bearer " . $this->getAccessToken()]);

            $res = Http::
            // dd()->
            retry(3, 10)
                ->withHeaders($headers)
                ->$method($endpoint,  $payload);
            return $res;
        } catch (\Exception $e) {
            Log::channel("xerowebhook")->error($e->getMessage());
            return null;
        }
    }
}
