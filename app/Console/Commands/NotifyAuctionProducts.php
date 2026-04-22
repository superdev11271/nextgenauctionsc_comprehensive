<?php
namespace App\Console\Commands;

use App\Http\Controllers\PushNotificationController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\NotifyAuctionUser;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class NotifyAuctionProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NotifyAuctionProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about auction products on their wishlist.';
    protected $pushNotificationController;
    protected $notificationTamplate;
    public function __construct()
    {
        parent::__construct();
        $this->notificationTamplate  = EmailTemplate::firstWhere('name', 'auction.expire');
        $this->pushNotificationController = new PushNotificationController();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // upcoming_auction_days key name refers to the hours 
        $ongoingMailSending = explode(',', get_setting('live_auction_days') ?? '1,2,3');

        DB::table('watchlist_view')
                ->where('auction_product', 1)
                ->where('email_sent', false)
                ->where('auction_start_date', '<=', strtotime("now"))
                ->where('auction_end_date', '>=', strtotime("now"))
                ->orderBy('id')
                ->chunk(1000, function ($watchlists) use ( $ongoingMailSending) {
                    if($watchlists){
                        foreach ($watchlists as $watchlist) {
                            $EndDate = is_numeric($watchlist->auction_end_date)
                                ? Carbon::createFromTimestamp($watchlist->auction_end_date)
                                : Carbon::parse($watchlist->auction_end_date);

                            $currentTime = Carbon::now();
                            
                            if (get_setting('auction_time_type') === 'hours') {
                                $untilAuctionEnds = $EndDate->diffInHours($currentTime);
                            } elseif (get_setting('auction_time_type') === 'minutes') {
                                $untilAuctionEnds = $EndDate->diffInMinutes($currentTime);
                            } else {
                                $untilAuctionEnds = $EndDate->diffInDays($currentTime);  
                            }
                            if (in_array($untilAuctionEnds, $ongoingMailSending)) {
                                dispatch(new NotifyAuctionUser($watchlist, $ongoingMailSending, [], false, true, $untilAuctionEnds));
                                $this->pushNotificationController->sendBrowserNotification($watchlist->user_id, $watchlist->product_id, $this->notificationTamplate);

                            }
                        }
                    }
                });

        return 0;
    }
}
