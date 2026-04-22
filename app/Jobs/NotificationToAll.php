<?php

namespace App\Jobs;

use App\Http\Controllers\EmailTemplateController;
use App\Models\EmailTemplate;
use App\Models\NotificationSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class NotificationToAll implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $vapidPublicKey;
    public $vapidPrivateKey;
    public $webPush;


    public function __construct()
    {
        // Load VAPID keys from environment variables
        $this->vapidPublicKey = env('VAPID_PUBLIC_KEY');
        $this->vapidPrivateKey = env('VAPID_PRIVATE_KEY');
        // Initialize the WebPush instance

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:your-email@example.com', // Replace with your actual email
                'publicKey' => $this->vapidPublicKey,
                'privateKey' => $this->vapidPrivateKey,
            ],
        ]);

        $mailTamplate  = EmailTemplate::firstWhere('name', 'custom');
        $subscriptions = NotificationSubscription::all();

        $notificationData = [
            'icon' => uploaded_asset(get_setting('site_icon')),
            'url' => $mailTamplate->redirect_url
        ];


        // Send notification to each subscription
        foreach ($subscriptions as $subscription) {
            $template = (new EmailTemplateController)->prepareTamplate($subscription->user,  null, $mailTamplate);
            $notificationData = array_merge($notificationData, $template);
            $subscription = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->p256dh,
                    'auth' => $subscription->auth,
                ]
            ]);

            $this->webPush->queueNotification(
                $subscription,
                json_encode($notificationData)
            );
        }

        // Flush pending notifications
        foreach ($this->webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                echo "[v] Notification sent successfully for subscription {$endpoint}.";
            } else {
                DB::table('subscriptions')->where('endpoint', $endpoint)->delete();

                echo "[x] Failed to send notification for subscription {$endpoint}: {$report->getReason()}";
            }
        }
    }
}
