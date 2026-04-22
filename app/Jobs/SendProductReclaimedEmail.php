<?php

// app/Jobs/SendProductReclaimedEmail.php

namespace App\Jobs;

use App\Mail\ProductReclaimed;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendProductReclaimedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productName;
    protected $userEmail;
    protected $userName;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($productName, $userEmail, $userName)
    {
        $this->productName = $productName;
        $this->userEmail = $userEmail;
        $this->userName = $userName;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Send the email
        Mail::to($this->userEmail)->send(new ProductReclaimed($this->productName, $this->userName));
    }

}

