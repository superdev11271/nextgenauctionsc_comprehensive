<?php

namespace App\Events;

use App\Models\AuctionProductBid;
use App\Models\Product;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;


class BidEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('nexgen');
    }

    public function broadcastWith()
    {
        $product = $this->product;
        $highestBidAmount = $product->bids()->max("amount");
        return [
            'product_id' => $product->id,
            "end_time" => date('Y/m/d H:i:s', $product->auction_end_date),
            "end_time_unixtime" => $product->auction_end_date,
            'current_bid_amount'=>$highestBidAmount,
            'next_bid'=>get_next_bid_amount($highestBidAmount, true),
        ];
    }

    public function broadcastAs()
    {
        return 'bid_update';
    }
}
