<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayoutNotification extends Notification
{
    use Queueable;

    protected $user;
    protected $amount;
    protected $status;
    protected $txn_code;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $amount, $status = '',$txn_code='')
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->status = $status;
        $this->txn_code = $txn_code;
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
        $array['view'] = 'emails.payoutnotification';
        $array['subject'] = translate("Your Requested Payout Amount Has Been Approved");
        $array['user_name'] = $this->user->name;
        $array['amount'] = $this->amount;
        $array['status'] = $this->status;
        $array['txn_code'] = $this->txn_code;
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
            'user_id'        => $this->user['id'],
            'user_type'      => $this->user['user_type'],
            'name'           => $this->user['name'],
            'payment_amount' => $this->amount, 
            'status'         => $this->status
        ];
    }
}
