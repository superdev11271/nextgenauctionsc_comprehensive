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

class NotifyAuctionUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $watchlist;
    protected $upcomingMailSendingHours;
    protected $ongoingMailSendingHours;
    protected $isUpcoming;
    protected $isOngoing;
    protected $hours;

    public function __construct($watchlist, $ongoingMailSendingHours, $upcomingMailSendingHours, $isUpcoming, $isOngoing, $hours)
    {
        $this->watchlist = $watchlist;
        $this->ongoingMailSendingHours = $ongoingMailSendingHours;
        $this->upcomingMailSendingHours = $upcomingMailSendingHours;
        $this->isUpcoming = $isUpcoming;
        $this->isOngoing = $isOngoing;
        $this->hours = $hours;
    }

    public function handle()
    {
        if (!$this->watchlist) {
            return;
        }
        if ($this->watchlist->auction_product == 1 && $this->isUpcoming) { 
            $this->sendAuctionEmail($this->watchlist, 'Upcoming', count($this->upcomingMailSendingHours), $this->hours);
        }

        if ($this->watchlist->auction_product == 1 && $this->isOngoing) {
            $this->sendAuctionEmail($this->watchlist, 'Ongoing', count($this->ongoingMailSendingHours), $this->hours);
        }
    }

    private function sendAuctionEmail($watchlist, $auctionStatus = "", $MaxEmailSendingLimit, $hours = "")
    {
        if (!empty($watchlist->user_email)) {
            $array = [
                'view' => 'emails.auction-product-notification',
                'subject' => translate('Auction Expiry Notification'),
                'auction_status' => $auctionStatus,
                'link' => url('auction-product', $watchlist->product_slug),
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
                Log::error('Bidder Notify Fail: ' . $e->getMessage());
            }
        }
    }
}
