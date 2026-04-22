<?php

namespace App\Console\Commands;

use App\Http\Controllers\PushNotificationController;
use Illuminate\Console\Command;
use App\Jobs\NotifyAuctionUser;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class NotifyUpcomingAuctions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NotifyUpcomingAuctionProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about auction products on their wishlist.';

    /**
     * Execute the console command.
     *
     * @return int
     */    protected $pushNotificationController;
    protected $notificationTamplate;



    public function __construct()
    {
        parent::__construct();
        $this->notificationTamplate  = EmailTemplate::firstWhere('name', 'auction.start');
        $this->pushNotificationController = new PushNotificationController();
    }
    public function handle()
    {
       
        
        $upcomingMailSending = explode(',', get_setting('upcoming_auction_days') ?? '1,2,3');

        DB::table('watchlist_view')
            ->where('auction_product', 1)
            ->where('email_sent', false)
            ->where('auction_start_date', '>', Carbon::now()) 
            ->orderBy('id')
            ->chunk(1000, function ($watchlists) use ($upcomingMailSending) {
                if($watchlists){
                    foreach ($watchlists as $watchlist) {
                        $StartDate = is_numeric($watchlist->auction_start_date)
                            ? Carbon::createFromTimestamp($watchlist->auction_start_date)
                            : Carbon::parse($watchlist->auction_start_date);
    
                        $currentTime = Carbon::now();
    
                        if (get_setting('auction_time_type') === 'minutes') {
                            $untilAuctionStarts = $StartDate->diffInMinutes($currentTime);
                        } elseif (get_setting('auction_time_type') === 'hours') {
                            $untilAuctionStarts = $StartDate->diffInHours($currentTime);
                        } else {
                            $untilAuctionStarts = $StartDate->diffInDays($currentTime);
                        }
                            
                        if (in_array($untilAuctionStarts, $upcomingMailSending)) {
                            dispatch(new NotifyAuctionUser($watchlist, [], $upcomingMailSending, true, false, $untilAuctionStarts));
                            $this->pushNotificationController->sendBrowserNotification($watchlist->user_id, $watchlist->product_id, $this->notificationTamplate);
    
                        }
                    }
                }

            });

        return 0;
    }
}
