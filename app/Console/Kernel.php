<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ReservedPriceCronJob;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
         \App\Console\Commands\CheckEndedAuctions::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('reserved_price_cron_job')->everyTenMinutes();
        $schedule->command('auctions:check')->everyMinute();
        // $schedule->command('queue:work --queue=web_push_notification,send-emails,default --daemon --timeout=0 --tries=12 --stop-when-empty --memory=6144')->everyMinute()->withoutOverlapping();
        // Determine the auction time type
        $auctionTimeType = get_setting('auction_time_type');

        if ($auctionTimeType === 'hours') {
            $schedule->command('NotifyAuctionProducts')->everyThirtyMinutes();
            $schedule->command('NotifyMarketplaceProducts')->everyThirtyMinutes();
            $schedule->command('NotifyUpcomingAuctionProducts')->everyThirtyMinutes();
        } elseif ($auctionTimeType === 'minutes') {
            $schedule->command('NotifyAuctionProducts')->everyMinute();
            $schedule->command('NotifyMarketplaceProducts')->everyMinute();
            $schedule->command('NotifyUpcomingAuctionProducts')->everyMinute();
        } else {
            $schedule->command('NotifyAuctionProducts')->dailyAt('00:00');
            $schedule->command('NotifyMarketplaceProducts')->dailyAt('00:00');
            $schedule->command('NotifyUpcomingAuctionProducts')->dailyAt('00:00');
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
