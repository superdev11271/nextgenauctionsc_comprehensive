<?php

// app/Http/Controllers/XeroController.php
namespace App\Http\Controllers;

use App\Jobs\XeroInvoiceUpdateEvent;
use App\Models\XeroWebHook;
use Auth;

use Illuminate\Http\Request;
use App\Services\XeroService;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class XeroController extends Controller
{
    private $webhookKey = 'u3JsgCYidrlBqcdxgCwZQ+OjnZ9WwX5a7UmpiKX9T7LI7DW+PtNsE4JQWsH4loc8YtsH8EZPEjfLD25bzPelHA==';

    public function verifyHook()
    {
        Log::channel("xerowebhook")->info(request());

        $rawPayload = file_get_contents('php://input');

        $computedSignatureKey = base64_encode(hash_hmac('sha256', $rawPayload, $this->webhookKey, true));

        $xeroSignatureKey = $_SERVER['HTTP_X_XERO_SIGNATURE'];

        $isEqual = false;
        if (hash_equals($computedSignatureKey, $xeroSignatureKey)) {
            $isEqual = true;
            http_response_code(200);
        } else {
            http_response_code(401);
        }

        $filedata = "";
        $filedata .= "\n---- Request body ----\n";
        $filedata .= $rawPayload . "\n";
        $filedata .= "\n---- Signature key ----";
        $filedata .= "\nComputed signature key:\n";
        $filedata .= $computedSignatureKey;
        $filedata .= "\nXero signature key:\n";
        $filedata .= $xeroSignatureKey;

        $filedata .= "\n\n---- Result ----\n";
        if ($isEqual) {
            $filedata .= "Match";
            Log::channel("xerowebhook")->info($filedata);
        } else {
            $filedata .= "Not match";
            Log::channel("xerowebhook")->info($filedata);
        }
        return $isEqual ? true : false;
    }

    public function xeroWebHookEndPoint()
    {
        $res = $this->verifyHook();
        echo "verify hook".http_response_code();

        if($res == false) return;

        Log::channel("xerowebhook")->info("Verified access granted");

        $event = isset(request()->events[0])?request()->events[0]:null;


        // Handle invoice update event
        if ($event != null && $event['eventCategory'] == 'INVOICE' && $event['eventType'] == "UPDATE") {
            Log::channel("xerowebhook")->info("Invoice has been updated", [$event['resourceUrl']]);
            $hookModal = $this->saveUpdatedInvoiceURL($event);
            Log::channel("xerowebhook")->info("Modal created", [$hookModal]);
            if($hookModal) XeroInvoiceUpdateEvent::dispatch($hookModal);
            return;
        }
        return;

    }
    public function saveUpdatedInvoiceURL($event)
    {
        return  XeroWebHook::create([
            "resource_url"=>$event['resourceUrl'],
            "event_category"=>$event['eventCategory'],
            "event_type"=>$event['eventType']
        ]);
    }
}

// convert xml into json
// $xmlstring = simplexml_load_string($xml);
// $json = json_encode($xmlstring);
