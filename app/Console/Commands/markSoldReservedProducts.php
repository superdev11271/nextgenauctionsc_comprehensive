<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class markSoldReservedProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:mark_sold_reserved_products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command picks up auction products which auction time is over and reserved price is met and mark them as sold to the highest bidder.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::onlyAuctionProducts()
                            ->where('auction_end_date', '<=', strtotime("now"))
                            ->get();

        foreach ($products as $product) {
            $highestBid = $product->getHighestBid();

            // skip if the reserve price is not met. and let the seller decide what to do.
            if($product->reserved_price > $highestBid?->amount) continue;
            $product->sold_to = $highestBid->user_id;
            $product->sold_status = "sold";
            $product->save();
        }
    }
}
