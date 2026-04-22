<?php

namespace App\Notifications;

use App\Models\EmailTemplate;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LostBidNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product)
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $template = EmailTemplate::where('name', 'auction_lost')->first();

        $array = [
            'view' => 'emails.lostbidNotification',
            'subject' => $template?->subject ?? "Auction Ended - Reserve Not Met",
            'body' => $template?->body ?? 'Sorry, reserve was not met. Please check other offers.',
            'auction_no' => $this->product->getFormattedAuctionNumber() ?? $this->product->name,
        ];

        return (new MailMessage)
            ->subject($array['subject'] . ' - ' . env('APP_NAME'))
            ->view($array['view'], ['array' => $array]);
    }
}
