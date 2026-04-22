<?php

namespace App\Http\Controllers;

use App\Events\BidEvent;
use App\Jobs\XeroCreateInvoice;
use App\Models\Bid;
use App\Notifications\WinBidNotification;
use Illuminate\Http\Request;
use App\Models\AuctionProductBid;
use App\Models\Product;
use Auth;
use Mail;
use DB;
use App\Mail\AuctionBidMailManager;
use App\Models\Bid as BidLog;
use App\Notifications\ReserveNotMetNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class AuctionProductBidController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_auction_product_bids'])->only('product_bids_admin');
        $this->middleware(['permission:delete_auction_product_bids'])->only('bid_destroy_admin');
    }

    public function cancelAutobid(Request $request, $product)
    {
        $bid = $this->find_or_new_bid($product,Auth::user()->id);
        $bid->autobid_amount = null;
        $bid->save();
        flash(translate('Auto Bid Cancelled Successfully.'))->success();
        return redirect()->back();
    }
    public function index(Request $request)
    {
        $query = Db::table('auction_product_bids')
            ->orderBy('auction_product_bids.id', 'desc')
            ->join('products', 'auction_product_bids.product_id', '=', 'products.id')
            ->where('auction_product_bids.user_id', Auth::user()->id)
            ->select('auction_product_bids.id')
            ->distinct();

        if ($request->status === 'won') {
            $query->where('products.sold_status', 'sold')
                ->where('products.sold_to', Auth::user()->id);
        } elseif ($request->status === 'lost') {
            $query->where('products.sold_status', 'sold')
                ->where('products.sold_to', '!=', Auth::user()->id);
        }

        // dd($query);
        $bids = $query->paginate(10)->appends(['status' => $request->status]);
        return view('auction.frontend.xthome.my_bidded_products', compact('bids'));
    }

    public function validate_and_store(Request $request)
    {
        if (!Auth::user()->hasVerifiedEmail())
           return prepare_response(['status' => false, 'msg' => translate('Verify Your Email Address.')]);

        if (!$this->isBidProfileComplete(Auth::user()))
            return prepare_response([
                'status' => false,
                'msg' => translate('Please complete your profile before bidding. Update your address, ID, and business details in your profile page.'),
                'redirect_to' => route('profile')
            ]);

        if (Auth::user()->user_type == 'admin')
           return prepare_response(['status' => false, 'msg' => translate("Admin cannot Place Bid  to Sellers' Product.")]);

        $product = Product::findOrFail($request->product_id);

        if ($product->isAuctionOver())
            return prepare_response(['status' => false, 'msg' => translate("Auction is over please refresh the page."),"refresh_required" => true]);

        if (Auth::user()->id == $product->user_id)
           return prepare_response(['status' => false, 'msg' => translate("Seller cannot Place Bid to His Own Product.")]);

        $lastBid = $product->bids()->max("amount");
        request()->merge(["lastBidAmount" => $lastBid]);
        if ($lastBid > $request->amount)
           return prepare_response(['status' => false, 'msg' => translate('Place a bid greater than this amount: $' . $lastBid . ".")]);
        return $this->store($request);
    }

    public function store_standered_bid(AuctionProductBid $bid)
    {
        $bid->amount = request()->amount;
        $bid->save();
        $this->logBid($bid);
        $this->notify_previous_bider($bid);
        return AutobidController::placeAutobidIfavailable($bid)
                ?? prepare_response(['status' => true,
                'msg' => translate("Bid Placed Successfully"),"current_bid"=>request()->amount,
                "my_bid"=>request()->amount,
                "next_bid" => get_next_bid_amount(request()->amount, true)]);
    }

    public function store_autobid(Request $request, AuctionProductBid $currentBid)
    {

        $heighest_bid = request()->lastBidAmount;

        $incrementAmount = get_next_bid_amount($heighest_bid);
        $currentBid->autobid_amount = $request->amount;

        $previousAutobid = AutobidController::previousAutobid($currentBid);

        // current_bid is the first or the highest autobid
        if ($previousAutobid == null) {
            return AutobidController::storeAuto($currentBid, $incrementAmount);
        }

        // Higher Autobid available
        // if lastBid is $100 current autobid $500 and there is previous autobid on $1000: set previous autobid to $510
        if($previousAutobid->autobid_amount > $currentBid->autobid_amount) {
            return AutobidController::trigerPreviousAutobid($previousAutobid, $currentBid);
        }

        // if lastBid is $100 current autobid $1000 and there is previous autobid on $500: set current autobid to $510
        return AutobidController::placeCurrentAutoBidOnPreviousAutobid($currentBid,$previousAutobid,$incrementAmount);
    }

    public function store(Request $request)
    {
        $current_bid = $this->find_or_new_bid($request->product_id, Auth::user()->id);

        $response = $request->autobid
                    ?$this->store_autobid($request, $current_bid)
                    :$this->store_standered_bid($current_bid);


        // Event fire
        $product = $current_bid->product;
        $this->fireBidEvent($product);
        return $response ?? prepare_response(['status' => false, 'msg' => translate('Something went wrong..')]);
    }

    public static function fireBidEvent($product)
    {
        $whenTimeShouldBeIncremented = intval(get_setting('auc_when_time_increment'));
        $productTime = Carbon::createFromTimestamp($product->auction_end_date);
        $now = Carbon::now();

        $logData = 'Product End: '.$productTime->toDateTimeString().' Now:'.$now->toDateTimeString().' After Increment:'.$now->addSeconds($whenTimeShouldBeIncremented);
        if ($productTime <= $now->addSeconds($whenTimeShouldBeIncremented)){
            $product->auction_end_date += getIncrementalAuctionTime();
            $product->save();
        }
        if(get_setting('pusher_status') == 1) event(new BidEvent($product));
    }

    public static  function logBid(AuctionProductBid $bid, $bid_type = "standard")
    {
        $bids_log = new BidLog;
        $bids_log->auction_id = $bid->id;
        $bids_log->bid_type = $bid_type;
        $bids_log->user_id = $bid->user_id;
        $bids_log->amount = $bid->amount;
        $bids_log->save();
    }
    public function find_or_new_bid($product_id, $user_id)
    {
        $bid = AuctionProductBid::where('product_id', $product_id)->where('user_id', $user_id)->first();
        if ($bid) {
            return $bid;
        }
        $bid =  new AuctionProductBid;
        $bid->user_id = Auth::user()->id;
        $bid->product_id = request()->product_id;
        return $bid;
    }

    public static function notify_previous_bider(AuctionProductBid $bid, $bidType = "standard")
    {
        // todo use bid type to modify the mail tamplate
        $secound_max_bid = AuctionProductBid::where('product_id', $bid->product_id)->orderBy('amount', 'desc')->skip(1)->first();

        if ($secound_max_bid == null) return;

        $product = $bid->product;
        $array['view'] = 'emails.auction_bid';
        $array['subject'] = translate(' Someone Placed a Higher Bid Than Yours');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = 'Hi! A user bidded more then you for the product, ' . $product->name . '. ' . 'Highest bid amount: ' . $bid->amount;
        $array['higest_bid'] = $bid->amount;
        $array['link'] = route('auction-product', $product->slug);
        $array['auction_no'] = $product->getFormattedAuctionNumber() ?? $product->name;
        try {
            Mail::to($secound_max_bid->user->email)->queue(new AuctionBidMailManager($array));
        } catch (\Exception $e) {
            Log::error("Failed to send notification mail to previous bidder: {$secound_max_bid->user->email} - Error: {$e->getMessage()}");
            return redirect()->back();
        }
    }

    public function product_bids_admin($id)
    {
        $id = decrypt($id);
        $product = Product::where('id', $id)->first();
        $bids = AuctionProductBid::latest()->where('product_id', $id)->paginate(15);
        return view('auction.auction_products.bids', compact('bids', 'product'));
    }

    public function product_bids_seller($id)
    {
        $id = decrypt($id);
        $product = Product::where('id', $id)->first();
        $bids = AuctionProductBid::latest()->where('product_id', $id)->paginate(15);
        return view('auction.frontend.seller.auction_products_bids', compact('bids', 'product'));
    }


    public function bid_destroy_admin($id)
    {
        AuctionProductBid::destroy($id);
        flash(translate('Bid deleted successfully'))->success();
        return back();
    }

    public function bid_destroy_seller($id)
    {
        $id = decrypt($id);
        AuctionProductBid::destroy($id);
        flash(translate('Bid deleted successfully'))->success();
        return back();
    }
    public function acceptBidOffer(Request $request, AuctionProductBid $bid)
    {
        $bid->product->sold_to = $bid->user_id;
        $bid->product->sold_status = "sold";
        $bid->product->save();
        // addAuctionProductIntoCart($bid->user_id,$bid->product);
        // XeroCreateInvoice::dispatch($bid->id);
        $bid?->user?->notify(new WinBidNotification($bid->product));
        flash(translate('Product has been sold successfullyy.'))->success();
        return back();
    }
    public function rejectBidOffer(Request $request, AuctionProductBid $bid)
    {
        $bid->status = "closed";
        $bid->save();
        flash(translate('Bid has been rejected.'))->success();
        $this->notifyAutobiderBidExceeded($bid);
        return back();
    }
    public static function notifyAutobiderBidPlaced(AuctionProductBid $autobidder)
    {
        $product = $autobidder->product;
        $bidderName = $autobidder->user->name;
        $array['view'] = 'emails.autobidplacedsuccess';
        $array['subject'] = translate('Autobid successfully placed.');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['user_name'] = $bidderName;
        $array['current_bided_amount'] = '';
        $array['link'] = route('auction-product', $product->slug);
        try {
            Mail::to($autobidder->user->email)->queue(new AuctionBidMailManager($array));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }
    public static function notifyAutobiderBidExceeded(AuctionProductBid $autobidder)
    {
        $product = $autobidder->product;
        $bidderName = $autobidder->user->name;
        $array['view'] = 'emails.autobidamountexceeded';
        $array['subject'] = translate('Notify Autobid limit has exceeded');
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['user_name'] = $bidderName;
        $array['current_bided_amount'] = '';
        $array['link'] = route('auction-product', $product->slug);
        try {
            Mail::to($autobidder->user->email)->queue(new AuctionBidMailManager($array));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }
    public static function notifyBidders($product){
        // This function is used to notify all revious bidder when reserved prive is not met.
        $product = Product::find(decrypt($product));
        $bids = $product->bids()->where("notified",0)->get();
        foreach ($bids as $bid) {
            $bid->user->notify(new ReserveNotMetNotification($bid));
            $bid->notified = 1;
            $bid->save();
        }
        flash(translate('Notification to bidders is being processed.'))->success();
        return redirect()->back();
    }

    private function isBidProfileComplete($user): bool
    {
        $requiredFields = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'govt_id',
            'street_number',
            'street_name',
            'suburb',
            'postal_code',
            'state',
        ];

        foreach ($requiredFields as $field) {
            if (blank($user->{$field})) {
                return false;
            }
        }

        if (blank($user->id_photo)) {
            return false;
        }

        if (is_null($user->is_business)) {
            return false;
        }

        if ((bool) $user->is_business && (blank($user->business_name) || blank($user->abn_can))) {
            return false;
        }

        return true;
    }
}
