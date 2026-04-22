<?php

namespace App\Notifications;

use App\Models\AuctionProductBid;
use App\Models\EmailTemplate;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WinBidNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
 public function toMail($notifiable)
{
    $template = EmailTemplate::where('name', 'auction_won')->first();

    $array = [
        'view' => 'emails.wonbidNotification',
        'subject' => $template?->subject ?? "Congratulations! You've Won the Auction",
        'body' => $template?->body ?? 'Default body message here.',
        'auction_no' => $this->product->getFormattedAuctionNumber() ?? $this->product->name,
    ];

    return (new MailMessage)
        ->subject($array['subject'] . ' - ' . env('APP_NAME'))
        ->view($array['view'], ['array' => $array]);
}

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
