<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ShopVerificationNotification extends Notification
{
    use Queueable;

    protected $shop;
    protected $status;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($shop, $status='submitted')
    {
        $this->shop = $shop;
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $array['view'] = 'emails.seller_verifiction_success';
        $array['subject'] = translate("Congratulations! You Are Now a Verified Vendor on NextGen Auctions & Marketplace");
        $array['user_name'] = $notifiable->name ??'';
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
            'name'  => $this->shop['name'],
            'id'    => $this->shop['id'],
            'status'=> $this->status
        ];
    }
}
