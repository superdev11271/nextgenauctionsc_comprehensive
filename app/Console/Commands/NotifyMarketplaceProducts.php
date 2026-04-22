<?php

namespace App\Console\Commands;

use App\Http\Controllers\PushNotificationController;
use Illuminate\Console\Command;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Jobs\NotifyMarketplaceUser;
use App\Models\EmailTemplate;

class NotifyMarketplaceProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'NotifyMarketplaceProducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users about marketplace products on their wishlist.';
    protected $pushNotificationController;
    protected $notificationTamplate;



    public function __construct()
    {
        parent::__construct();
        $this->notificationTamplate  = EmailTemplate::firstWhere('name', 'marketplace.expire');
        $this->pushNotificationController = new PushNotificationController();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // marketplace_product_days key name refers to the hours
        $marketplaceHours = explode(',', get_setting('marketplace_product_days') ?? '1,2,3');

        DB::table('watchlist_view')
            ->where('auction_product', 0)
            ->where('email_sent', false)
            ->orderBy('id')
            ->chunk(1000, function ($watchlists) use ($marketplaceHours) {
                if($watchlists){
                    foreach ($watchlists as $watchlist) {
                        $currentTime = Carbon::now();

                        $EndDate = is_numeric($watchlist->auction_end_date)
                            ? Carbon::createFromTimestamp($watchlist->auction_end_date)
                            : Carbon::parse($watchlist->auction_end_date);

                        if (get_setting('marketplace_product_expiry')) {
                            if (get_setting('auction_time_type') === 'hours') {
                                $productExpiry = $currentTime->diffInHours($EndDate);
                            } elseif (get_setting('auction_time_type') === 'minutes') {
                                $productExpiry = $currentTime->diffInMinutes($EndDate);
                            } else {
                                $productExpiry = $currentTime->diffInDays($EndDate);
                            }                        
                            if (in_array($productExpiry, $marketplaceHours)) {
                                dispatch(new NotifyMarketplaceUser($watchlist, $marketplaceHours, $productExpiry ));
                                $this->pushNotificationController->sendBrowserNotification($watchlist->user_id, $watchlist->product_id, $this->notificationTamplate);
                            }
                        }
                    }
                }
            });

        return 0;
    }
}
