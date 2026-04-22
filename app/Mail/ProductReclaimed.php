<?php

// app/Mail/ProductReclaimed.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductReclaimed extends Mailable
{
    use Queueable, SerializesModels;

    public $productName;
    public $userName;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($productName, $userName)
    {
        $this->productName = $productName;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Product Reclaimed Notification')
                    ->view('emails.product_reclaimed');
    }
}
