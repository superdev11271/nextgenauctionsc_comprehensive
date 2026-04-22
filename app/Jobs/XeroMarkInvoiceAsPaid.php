<?php

namespace App\Jobs;

use App\Models\AuctionProductBid;
use App\Models\Order;
use App\Services\XeroService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class XeroMarkInvoiceAsPaid implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5; // Number of retry attempts

    public $retryAfter = 60; // Retry after 60 seconds

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $bidId;
    public function __construct($bidId)
    {
        $this->bidId = $bidId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bid = AuctionProductBid::find($this->bidId);
        $invoice = $bid->xero;
        $invoiceNumber = $invoice?->invoice_number;
        $amount = $invoice?->total_amount;

        if($invoiceNumber == null || $amount == null){
            Log::channel("xerowebhook")->error("Cannot update invoice paid status. Bid -> $bid");
            return;
        }
        $invoice = (new XeroService)->updateInvoiceStatus($invoiceNumber, $amount);

        if($invoice==false){
            throw new Exception("Cant update invoice status..");
        }
    }
    public function failed(\Exception $exception)
    {
        // Handle the failure, e.g., send notification
        Log::channel("xerowebhook")->error('Job failed after all retries: ' . $exception->getMessage());
    }
}
