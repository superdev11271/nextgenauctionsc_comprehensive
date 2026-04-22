<?php

namespace App\Jobs;

use App\Models\AuctionProductBid;
use App\Models\Order;
use App\Models\XeroWebHook;
use App\Services\XeroService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class XeroInvoiceUpdateEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $webhook;
    public function __construct(XeroWebHook $webhook)
    {
        $this->webhook = $webhook;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $invoiceData = (new XeroService)->fetchInvoice($this->webhook->id);

        Log::channel("xerowebhook")->info($invoiceData);

        $invoiceNumber = $invoiceData["Invoices"][0]["InvoiceNumber"];
        $dueAmount = $invoiceData["Invoices"][0]["AmountDue"];

        if(($bid = $this->getBid($invoiceNumber)) == null) return;

        $product_id = $bid->product_id;

        $order = Order::where(["user_id" => $bid->user_id])->whereHas("orderDetails", function ($q) use ($product_id) {
            $q->where("product_id", $product_id);
        })->first();

        if($dueAmount == 0)
        $order->payment_status = 'paid';
        $order->payment_details = "Xero Update";
        $order->payment_type = "Xero Manual";
        $order->save();
        return;
    }

    public function getBid($invoiceNumber){
        $bid = AuctionProductBid::whereHas("xero", function ($q) use ($invoiceNumber) {
            $q->where("invoice_number", $invoiceNumber);
        })->first();

        if ($bid == null) {
            $this->webhook->status = "rejected"; //processed
            $this->webhook->status_description = "Not created using this application.";
            $this->webhook->save();
            return null;
        }
        $this->webhook->status = "processed";
        $this->webhook->save();
        return $bid;
    }
}
