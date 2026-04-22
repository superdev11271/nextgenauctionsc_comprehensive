<?php

namespace App\Http\Controllers;

use App\Http\Resources\V2\Auction\AuctionBidProducts;
use App\Models\AuctionProductBid;
use App\Models\Chat;
use App\Models\ChatTamplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BidderChatController extends Controller
{
    public function store(Request $request)
    {
        $validData =  $request->validate([
            "chat_tamplate_id" => "required|exists:chat_tamplates,id",
            "bid_id" => "required:exists:auction_product_bids,id",
            "amount" => "sometimes|required"
        ], [
            "chat_tamplate_id.required" => "Please Select a message.",
            "amount.required" => "Please Enter Amount.",
        ]);

        $isAmountRequired = ChatTamplate::find($validData["chat_tamplate_id"])->with_amount;
        $bid =  AuctionProductBid::find($validData["bid_id"]);

        $current_bid_amount = $bid->product->bids()->max("amount");

        if ($isAmountRequired && $validData["amount"]  <= $current_bid_amount) {
            return response()->json(["error" => "Place a bid greater than amount $ $current_bid_amount"], 422);
        }

        // if ($bid->product->isAuctionOver())
        //     return response()->json(["error" => "Auction is over."], 422);

        if ($isAmountRequired) {
            $bid->amount = $validData["amount"];
            $bid->save();
        } else {
            unset($validData["amount"]);
        }

        $validData["product_id"] = $bid->product_id;
        $validData["receiver"] = $bid->product->user_id;
        $validData["sender"] = auth()->id();

        $chat = Chat::create($validData);
        $chat->load('tamplate');

        return ["data" => $chat];
    }

    public function checkNewMsg(Request $request, Chat $chat)
    {
        $newChat = Chat::where(
            function ($query) use ($chat) {
                return $query->where(["sender" => $chat->receiver, "receiver" => $chat->sender,"bid_id"=> $chat->bid_id])
                    ->orWhere(function ($query) use ($chat) {
                        $query->where(["sender"=> $chat->sender,"receiver" => $chat->receiver,"bid_id"=> $chat->bid_id]);
                    });
                }
        )
        ->where("id", ">", $chat->id)
        ->with("tamplate:id,message")
        ->select(["id", "amount", "chat_tamplate_id"])
        ->get();

        // dd($newChat);
        markViewd($newChat?->last()?->id, $chat->receiver);

        $bid = AuctionProductBid::find($chat->bid_id);
        $current_bid = $bid->product->bids()->max("amount");
        $my_bid = $bid->amount;
        return ["data" => $newChat, "current_bid" => $current_bid, "my_bid" => $my_bid, "refresh_required" => ($bid->product->sold_status != null)];
    }

    public function chatIndex(Request $request, $bid)
    {
        $bid = AuctionProductBid::find(decrypt($bid));

        if ($bid->product->sold_status != null) {
            return redirect()->route("bidded_products")
                ->with(
                    "notify_bidder",
                    $bid->product->sold_to == auth()->id() ?
                        "Congratulations! You've won the bid. You can now add the product to your cart and proceed to checkout."
                        : "Sorry, the product has been sold. Better luck next time!"
                );
        }

        $chatHistory = Chat::where(["sender" => $bid->user_id, "receiver" => $bid->product->user_id, "bid_id" => $bid->id])->orWhere(function ($query) use ($bid) {
            $query->where("sender", $bid->product->user_id)->where("receiver", $bid->user_id)->where("bid_id", $bid->id);
        })->get();

        $lastChat = $chatHistory?->last();
        markViewd($lastChat?->id, $bid->product->user_id);
        Cache::forget("notificationCount");
        $formats = ChatTamplate::where("used_by", "bidder")->get();
        return view('auction.frontend.xthome.xt_chat', compact("chatHistory", "bid", "formats"));
    }
}
