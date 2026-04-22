<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendAuctionSummaryToAdmin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

 public function handle()
{
    try {
        $adminEmail = config('mail.admin_email', env('MAIL_ADMIN_EMAIL', 'studioequine@outlook.com'));

        \Log::info("[SendAuctionSummaryToAdmin] Preparing to send to admin: {$adminEmail}");

        Mail::send('emails.auction_summary', [
            'reportData' => $this->reportData,
        ], function ($message) use ($adminEmail) {
            $message->to($adminEmail)->subject('Auction Summary Report');
        });

        if (count(Mail::failures()) > 0) {
            \Log::error('[SendAuctionSummaryToAdmin] Mail failures: ' . json_encode(Mail::failures()));
        } else {
            \Log::info("[SendAuctionSummaryToAdmin] Email sent successfully to: {$adminEmail}");
        }

    } catch (\Throwable $e) {
        \Log::error("[SendAuctionSummaryToAdmin] Failed to send email to admin");
        \Log::error($e->getMessage());
        \Log::error($e->getTraceAsString());
    }
}


}
