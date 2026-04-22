<?php

namespace App\Console\Commands;

use App\Jobs\XeroCreateInvoice;
use App\Models\AuctionProductBid;
use App\Models\Product;
use App\Notifications\HighestBidderReservedNotification;
use App\Notifications\WinBidNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReservedPriceCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reserved_price_cron_job';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command picks up the auction products that have not met the reserved price at the auction end and notify the seller and higest bidder.';

    /**
     * Execute the console command.\
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::onlyAuctionProducts()
            ->where('auction_end_date', '<=', strtotime("now"))
            ->where('notified_bidder', null)
            ->whereNull('sold_status')
            ->get();

        foreach ($products as $product) {
            $highestBid = $product->highestBid();
            if ($highestBid == null) continue;

            if ($product->reserved_price == null || $highestBid->amount >= $product?->reserved_price) {
                $this->reservedMet($product, $highestBid);
                continue;
            }

            $this->reservedNotMet($product, $highestBid);
            $product->notified_bidder = 1;
            $product->save();
        }
    }
    public function reservedMet(Product $product, AuctionProductBid $highestBid)
    {
        $product->sold_to = $highestBid->user_id;
        $product->sold_status = "sold";
        $product->save();
        // addAuctionProductIntoCart($highestBid->user_id,$product);
        $highestBid?->user?->notify(new WinBidNotification($product));
        // XeroCreateInvoice::dispatch($highestBid->id);
    }
    public function reservedNotMet(Product $product, AuctionProductBid $highestBid)
    {
        $highestBid->user?->notify(new HighestBidderReservedNotification($highestBid));
        $highestBid->notified = 1;
        $highestBid->save();
    }
}
