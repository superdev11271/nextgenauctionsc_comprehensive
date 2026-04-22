<?php

namespace App\Http\Controllers;

use App\Models\AuctionProductBid;
use App\Models\Chat;
use App\Models\ChatTamplate;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class SellerChatController extends Controller
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

        $bid =  AuctionProductBid::find($validData["bid_id"]);
        // $bid = decrypt($bid);
        $validData["product_id"] = $bid->product_id;
        $validData["receiver"] = $bid->user_id;
        $validData["sender"] = auth()->id();

        $chat = Chat::create($validData)
            ->load('tamplate:id,message')
            ->only(["id", "amount", "chat_tamplate_id", "tamplate"]);

        return response()->json($chat);
    }

    public function index(Request $request, Product $product,  $currentbid)
    {
        $currentbid = AuctionProductBid::find(decrypt($currentbid));
        if($product->sold_status != ""){
            return redirect()->route("seller.dashboard");
        }

        $bids = $product->bids;
        $authUserId = auth()->id();

        $chatHistory = Chat::where(["sender" => $currentbid->user_id, "receiver" => $authUserId, "bid_id" => $currentbid->id])->orWhere(function ($query) use ($authUserId, $currentbid) {
            $query->where("sender", $authUserId)->where("receiver", $currentbid->user_id)->where("bid_id", $currentbid->id);
        })->get();

        markViewd($chatHistory?->last()?->id, $currentbid->user_id);

        $formats = ChatTamplate::where("used_by", "seller")->get();
        return view('auction.frontend.seller.auction_products_chat', compact("product", "bids", "currentbid", "chatHistory", "formats"));
    }

    public function getChatHistory(Request $request, AuctionProductBid $bid)
    {
        // $bid = decrypt($bid);
        $authUserId = auth()->id();
        $chatHistory = Chat::where(["sender" => $bid->user_id, "receiver" => $authUserId, "bid_id" => $bid->id])->orWhere(function ($query) use ($authUserId, $bid) {
            $query->where("sender", $authUserId)->where("receiver", $bid->user_id)->where("bid_id", $bid->id);
        })->get();

        $history = "";
        foreach ($chatHistory as $key => $chat) {
            $msgTyep = $authUserId == $chat->sender ? 'repaly' : 'sender';
            $message = $chat->tamplate?->message;
            $amount = $chat->amount ? "Bid Amount:" . currency_format($chat->amount) : "";
            $history  .= "<li class='$msgTyep'> <p> $message <span class='fw-600'>$amount  </span></p>
            <span class='time'>{$chat->created_at->diffForHumans()}</span>
            </li>";
        }
        
        $name = $bid->user->name;
        $image = uploaded_asset($bid->user->avatar_original);
        $header_amount =  currency_format($bid->amount);
        $header = "<div class='d-flex align-items-center' >
                    <div class='flex-shrink-0'>
                        <img class='img-fluid w-35px rounded-circle'
                            src='$image'
                            alt='user img'>
                    </div>
                    <div class='flex-grow-1 ms-3'>
                        <h3>$name</h3>
                        <p class='text-success fw-600'>Bid: $header_amount
                        </div>
                </div>";

        markViewd($chatHistory?->last()?->id, $bid->user_id);

        return ["chat_header" => $header, "history" => $history, "user_id" => $bid->user_id];
    }

    public function getUpdatesAjax(Product $product)
    {
        return $product->bids()->withCount(['chats' => function ($query) {
            return  $query->where(['viewed'=> 0,"receiver"=>auth()->id()]);
        }])->get();
    }
}
