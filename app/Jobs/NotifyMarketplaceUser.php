<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ProductNotificationManager;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Mail;

class NotifyMarketplaceUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $watchlist;
    protected $marketplaceHours;
    protected $hours;

    public function __construct($watchlist, $marketplaceHours, $hours)
    {
        $this->watchlist = $watchlist;
        $this->marketplaceHours = $marketplaceHours;
        $this->hours = $hours;
    }

    public function handle()
    {
        if (!$this->watchlist || !$this->watchlist->auction_product == 0) {
            return;
        }
        if (get_setting('marketplace_product_expiry')) {
            $this->sendMarketProductEmail($this->watchlist, count($this->marketplaceHours), $this->hours);
        }
    }

    private function sendMarketProductEmail($watchlist, $MaxEmailSendingLimit, $hours = "")
    {
        if (!empty($watchlist->user_email) && !$watchlist->email_sent) {
            $array = [
                'view' => 'emails.marketplace-product-notification',
                'subject' => translate('Product Expiry Notification'),
                'link' => url('product', $watchlist->product_slug),
                'hours' => $hours,
                'image' => uploaded_asset($watchlist->product_thumbnail_img),
            ];

            try {
                Mail::to($watchlist->user_email)->queue((new ProductNotificationManager($array))->onQueue('send-emails'));
                DB::table('wishlists')->where('id', $watchlist->id)->increment('email_send_count');
                $watchlist->email_send_count += 1;
                if ($watchlist->email_send_count >= $MaxEmailSendingLimit) {
                    DB::table('wishlists')->where('id', $watchlist->id)->update(['email_sent' => true]);
                }
            } catch (\Exception $e) {
                Log::error('Failed to notify bidder for wishlist ID ' . $watchlist->id . ': ' . $e->getMessage());
            }
        }
    }
}
