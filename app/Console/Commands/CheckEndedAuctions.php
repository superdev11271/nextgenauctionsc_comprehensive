<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Notifications\WinBidNotification;
use App\Notifications\LostBidNotification;
use App\Jobs\XeroCreateInvoice;
use LostBidNotification as GlobalLostBidNotification;

class CheckEndedAuctions extends Command
{
    protected $signature = 'auctions:check';
    protected $description = 'Check for ended auctions and send appropriate notifications';

    public function handle()
    {
        \Log::info('[auctions:check] Running at: ' . now());
        $reportData = []; // ✅ collect summary for super admin
        $products = \App\Models\Product::whereBetween('auction_end_date', [
            now()->subMinutes(2)->timestamp,
            now()->timestamp
        ])
            ->where(function ($q) {
                $q->whereNull('sold_status')->orWhere('sold_status', '');
            })
            ->get();

        \Log::info("Found {$products->count()} ended auction(s) at " . now()->timestamp);

        foreach ($products as $product) {
            \Log::info("Checking Product ID: {$product->id}");

            $allBids = $product->bids()->with('user')->orderByDesc('amount')->get();

            if ($allBids->isEmpty()) {
                \Log::info("No bids for Product ID: {$product->id}");
                continue;
            }

            $highestBid = $product->getHighestBid(); // using your model method

            if (!$highestBid || !$highestBid->user) {
                \Log::warning("Highest bid missing user or bid for Product ID: {$product->id}");
                continue;
            }

            // ✅ Case 1: RESERVE MET — Notify winner, others get loss email
            if ($product->reserved_price <= $highestBid->amount) {
                if (!$highestBid->notified) {
                    \Log::info("RESERVE MET — Product SOLD to User ID: {$highestBid->user_id}");

                    $product->sold_status = 'sold';
                    $product->sold_to = $highestBid->user_id;
                    $product->save();

                    $highestBid->user->notify(new \App\Notifications\WinBidNotification($product));
                    // \App\Jobs\XeroCreateInvoice::dispatch($highestBid->id);

                    $highestBid->notified = 1;
                    $highestBid->save();
                } else {
                    \Log::info("Highest bidder (User ID: {$highestBid->user_id}) already notified, skipping...");
                }

                // Notify other bidders with loss email
                $notifiedUsers = [$highestBid->user_id];

                foreach ($allBids as $bid) {
                    if (!$bid->notified && $bid->user && !in_array($bid->user_id, $notifiedUsers)) {
                        $bid->user->notify(new \App\Notifications\LostBidNotification($product));

                        $bid->notified = 1;
                        $bid->save();

                        $notifiedUsers[] = $bid->user_id;

                        \Log::info("Notified losing bidder (User ID: {$bid->user_id}) for Product ID: {$product->id}");
                    }
                }
            }

            // ✅ Case 2: RESERVE NOT MET — Notify all unique bidders (including highest)
            else {
                \Log::info("RESERVE NOT MET — Notifying all bidders for Product ID: {$product->id}");

                $notifiedUsers = [];

                foreach ($allBids as $bid) {
                    if (!$bid->notified && $bid->user && !in_array($bid->user_id, $notifiedUsers)) {
                        $bid->user->notify(new \App\Notifications\LostBidNotification($product));

                        $bid->notified = 1;
                        $bid->save();

                        $notifiedUsers[] = $bid->user_id;

                        \Log::info("Notified losing bidder (User ID: {$bid->user_id}) for Product ID: {$product->id}");
                    }
                }
            }

            // ✅ Summary row for admin report
        $entry = [
            'product_name'   => $product->name,
            'product_url'    => route('product_bids.admin', encrypt($product->id)),
            'highest_bid'    => $highestBid?->amount ?? '—',
            'reserve_price'  => $product->reserved_price,
            'reserve_met'    => $product->reserved_price <= $highestBid?->amount,
            'status'         => $product->sold_status ?? 'Not Sold',
            'winner_name'    => $highestBid?->user?->name ?? '—',
            'winner_email'   => $highestBid?->user?->email ?? '—',
        ];

        \Log::info("Auction Summary Entry:", $entry);

        $reportData[] = $entry;
        }
        // ✅ Send report to Super Admin
          // ✅ Send summary email to admin
    if (!empty($reportData)) {
        \Log::info("Dispatching SendAuctionSummaryToAdmin job with " . count($reportData) . " items.");
        \App\Jobs\SendAuctionSummaryToAdmin::dispatch($reportData);
    } else {
        \Log::info("No auction summary to send.");
    }
    }
}
