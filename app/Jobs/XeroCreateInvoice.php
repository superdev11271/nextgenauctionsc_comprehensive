<?php

namespace App\Jobs;

use App\Models\AuctionProductBid;
use App\Models\XeroMeta;
use App\Services\XeroService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class XeroCreateInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5; // Number of retry attempts
    public $retryAfter = 60; // Retry after 60 seconds
    protected $bidId;
    public function __construct($bidId)
    {
        $this->bidId = $bidId;
    }
    public function handle()
    {
        // user product auction bid table as a pivot point to get all the details
        $bid = AuctionProductBid::find($this->bidId);
        $product = $bid->product;
        $user = $bid->user;
        $invoice = (new XeroService)->createInvoice($user, [$product]);

        if($invoice){
            $invoiceNumber = $invoice["Invoices"][0]["InvoiceNumber"];
            $invoiceID = $invoice["Invoices"][0]["InvoiceID"];
            $totalAmount = $invoice["Invoices"][0]["Total"];
            $invoiceModal = XeroMeta::create(["bid_id" => $this->bidId, "invoice_number" => $invoiceNumber, "total_amount" => $totalAmount,"invoice_id"=>$invoiceID]);
            $this->sendEmail($invoiceModal);
            Log::channel("xerowebhook")->info("Created invoice", $invoice);
            return true;
        }
        throw new Exception('Could not create invoice');
    }
    public function sendEmail($invoiceModal){
        $is_sent = (new XeroService())->mailXeroInvoice($invoiceModal->invoice_id);
        $invoiceModal->mail_sent = $is_sent?1:0;
        $invoiceModal->save();
    }
}

