<?php

namespace App\Http\Controllers;

use App\Mail\AuctionBidMailManager;
use App\Models\AuctionProductBid;
use App\Models\AutobidInterval;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AutobidController extends Controller
{
    public static function storeAuto(AuctionProductBid $currentBid, int $incrementAmount)
    {
        $nextAutobidAmont = request()->lastBidAmount + $incrementAmount;
        $currentBid->amount = $nextAutobidAmont;
        $currentBid->save();
        AuctionProductBidController::notifyAutobiderBidPlaced($currentBid);
        AuctionProductBidController::logBid($currentBid);
        AuctionProductBidController::notify_previous_bider($currentBid);

        return prepare_response([
            'status' => true,
            'msg' => translate("Auto Bid Placed Successfully"),
            "refresh_required" => true,
            "current_bid" => ($nextAutobidAmont),
            "my_bid" => (request()->amount)
        ]);
    }
    public static function trigerPreviousAutobid(AuctionProductBid $higherBid, AuctionProductBid $currentBid)
    {
        $currentBid->amount = $currentBid->autobid_amount;
        $currentBid->save();
        AuctionProductBidController::logBid($currentBid, "autobid");
        AuctionProductBidController::notify_previous_bider($currentBid);

        $incrementAmount = get_next_bid_amount($currentBid->autobid_amount);

        $nextAutobidAmont = $incrementAmount + $currentBid->autobid_amount;
        if ($nextAutobidAmont > $higherBid->autobid_amount) {
            $nextAutobidAmont = $higherBid->autobid_amount;
        };
        $higherBid->amount = $nextAutobidAmont;
        $higherBid->save();
        AuctionProductBidController::logBid($higherBid);
        AuctionProductBidController::notify_previous_bider($higherBid);
        return prepare_response([
            'status' => true,
            'msg' => translate("A autobid has been placed Amount: $nextAutobidAmont"),
            "current_bid" => ($nextAutobidAmont),
            "my_bid" => ($currentBid->autobid_amount),
            "next_bid" => get_next_bid_amount(request()->amount, true)
        ]);
    }

    public static function placeCurrentAutoBidOnPreviousAutobid(AuctionProductBid $current_bid, AuctionProductBid $previousAutobid, int $incrementAmount)
    {
        $nextAutobidAmont = $previousAutobid->autobid_amount + $incrementAmount;
        $nextAutobidAmont = $nextAutobidAmont > request()->amount ? request()->amount : $nextAutobidAmont;

        $current_bid->amount = $nextAutobidAmont;
        $current_bid->save();

        AuctionProductBidController::logBid($current_bid);
        AuctionProductBidController::notify_previous_bider($current_bid, "autobid");
        AuctionProductBidController::notifyAutobiderBidExceeded($previousAutobid);
        return prepare_response([
            'status' => true,
            'msg' => translate("You just placed a bid on auto bid."),
            "refresh_required" => true,
            "my_bid" => ($nextAutobidAmont),
            "current_bid" => ($nextAutobidAmont)
        ]);
    }
    public static function placeAutobidIfavailable(AuctionProductBid $current_bid)
    {
        $currentBidAmount = $current_bid->amount;

        $autobid = $current_bid->product->bids()
            ->where("autobid_amount", ">", $currentBidAmount)
            ->orderBy("autobid_amount", "desc")
            ->first();

        // Is there any autobid available
        if ($autobid == null) return null;

        $incrementAmount = get_next_bid_amount($currentBidAmount);
        $nextAutobidAmont = $incrementAmount + $currentBidAmount;

        $nextAutobidAmont = $nextAutobidAmont > $autobid->autobid_amount ? $autobid->autobid_amount : $nextAutobidAmont;

        $autobid->amount = $nextAutobidAmont;
        $autobid->save();
        AuctionProductBidController::logBid($autobid, "autobid");
        AuctionProductBidController::notify_previous_bider($autobid, "autobid");
        return prepare_response([
            'status' => true,
            'msg' => translate("A autobid has been placed Amount: $nextAutobidAmont"),
            "current_bid" => $nextAutobidAmont,
            "my_bid" => $current_bid->amount,
            "next_bid" => get_next_bid_amount(request()->amount, true)]);
    }

    public static function previousAutobid(AuctionProductBid $current_bid): ?AuctionProductBid
    {
        return $current_bid->product->bids()
            ->where("autobid_amount", ">", request()->lastBidAmount ?? 0)
            ->whereNotNull("autobid_amount")
            ->orderBy("autobid_amount", "desc")
            ->first();
    }
}
