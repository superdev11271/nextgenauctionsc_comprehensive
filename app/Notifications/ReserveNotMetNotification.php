<?php

namespace App\Notifications;

use App\Models\AuctionProductBid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReserveNotMetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public AuctionProductBid $bid)
    {
        //
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
        // Todo Use Mail Tamplate: Opportunity to Place Your Highest Offer: [Item Name/Auction Number]
        $array['view'] = 'emails.reserved_price__not_met';
        $array['subject'] = translate("Opportunity to Place Your Highest Offer:");
        $array['auction_no'] = $this->bid->product->getFormattedAuctionNumber();
        $array['link'] = route('chat.index', encrypt($this->bid->id));
        return (new MailMessage)
            ->view($array['view'], ['array' => $array])
            ->subject($array['subject'] . env('APP_NAME'));
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
