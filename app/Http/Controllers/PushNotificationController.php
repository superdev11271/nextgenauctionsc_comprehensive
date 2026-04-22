<?php

namespace App\Http\Controllers;

use App\Jobs\NotificationToAll;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\DB;
use Minishlink\WebPush\VAPID;

class PushNotificationController extends Controller
{
    public $vapidPublicKey;
    public $vapidPrivateKey;
    public $webPush;

    // Constructor
    public function __construct()
    {
        // Load VAPID keys from environment variables
        $this->vapidPublicKey = env('VAPID_PUBLIC_KEY');
        $this->vapidPrivateKey = env('VAPID_PRIVATE_KEY');

        // Initialize the WebPush instance
        $this->webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:your-email@example.com', // Replace with your actual email
                'publicKey' => $this->vapidPublicKey,
                'privateKey' => $this->vapidPrivateKey,
            ],
        ]);
    }

    public function subscribe(Request $request)
    {
        // Validate request
        $request->validate([
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $values = [
            'auth' => $request->keys['auth'],
            'p256dh' => $request->keys['p256dh'],
            'created_at' => now(),
        ];

        if (auth()?->id()) $values['user_id'] = auth()?->id();

        DB::table('subscriptions')->updateOrInsert([
            'endpoint' => $request->endpoint,
        ], $values);

        return response()->json(['success' => true]);
    }

    // Function to send push notification to all subscribers
    public function sendNotification(Request $request)
    {
        // Prepare notification content
        $notificationData = [
            'title' => 'Hi Sample Notification123',
            'body' => 'Hello This is a push notification mf',
            'icon' => uploaded_asset(get_setting('site_icon')),
            'url' => env('APP_URL')
        ];

        // Get all subscriptions from the database
        $subscriptions = DB::table('subscriptions')->get();

        if ($subscriptions->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No subscriptions found']);
        }

        // Create WebPush object with VAPID keys
        $webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:your-email@example.com',
                'publicKey' => $this->vapidPublicKey,
                'privateKey' => $this->vapidPrivateKey,
            ],
        ]);

        // Send notification to each subscription
        foreach ($subscriptions as $subscription) {
            $subscription = Subscription::create([
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->p256dh,
                    'auth' => $subscription->auth,
                ]
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode($notificationData)
            );
        }

        // Flush pending notifications
        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if ($report->isSuccess()) {
                echo "[v] Notification sent successfully for subscription {$endpoint}.";
            } else {
                echo "[x] Failed to send notification for subscription {$endpoint}: {$report->getReason()}";
            }
        }

        return response()->json(['success' => true]);
    }


    public function sendBrowserNotification($userId, $productId, EmailTemplate $mailTamplate)
    {
        $template  = EmailTemplate::firstWhere('name','custom');
        $user = User::find($userId);
        $product = Product::find($productId);

        $notificationData = [
            'icon' => uploaded_asset(get_setting('site_icon')),
            'url' => route($product->auction_product ? 'auction-product' : 'product', $product->slug)
        ];
        $template = (new EmailTemplateController)->prepareTamplate($user, $product, $mailTamplate);
        $notificationData = array_merge($notificationData, $template);

        $subscribedDevices = DB::table('subscriptions')
            ->where('user_id', $userId)
            ->get();

        if ($subscribedDevices->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No subscriptions found']);
        }

        foreach ($subscribedDevices as $device) {

            $subscription = Subscription::create([
                'endpoint' => $device->endpoint,
                'keys' => [
                    'p256dh' => $device->p256dh,
                    'auth' => $device->auth,
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

        return response()->json(['success' => true]);
    }
    public function sendBrowserNotificationToAll(Request $request)
    {
        dispatch(new NotificationToAll())
        ->onQueue('web_push_notification');

        flash(translate('Notification process started.'))->success();
        return back();
    }
}
