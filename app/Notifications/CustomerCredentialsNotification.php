<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomerCredentialsNotification extends Notification
{
    use Queueable;

    public $email;
    public $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $array['view'] = 'emails.customer_credentials';
        $array['subject'] = translate('Your Login Credentials');
        $array['email'] = $this->email;
        $array['password'] = $this->password;
        $array['name'] = $notifiable->name;
        $array['login_url'] = route('login');

        return (new MailMessage)
            ->view('emails.customer_credentials', ['array' => $array])
            ->subject(translate('Your Account Details - ') . env('APP_NAME'));
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
