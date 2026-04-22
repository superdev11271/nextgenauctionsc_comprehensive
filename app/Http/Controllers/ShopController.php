<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerRegistrationRequest;
use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\Seller;
use App\Models\BusinessSetting;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Support\Facades\Notification;
use DB;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
            if ((Auth::user()->user_type == 'admin')) {
                flash(translate('Admin or Customer cannot be a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'seller') {
                flash(translate('This user already a seller'))->error();
                return back();
            }
            if (Auth::user()->user_type == 'customer') {
                return view('frontend.xt-user.become-seller');
            }
        } else {
            return view('auth.'.get_setting('authentication_layout_select').'.seller_registration');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SellerRegistrationRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type = "seller";
        $user->password = Hash::make($request->password);
        $user->user_code = create_user_code($request->name);
        $user->verification_code= encrypt($user->user_code);


        if ($user->save()) {
            $shop = new Shop;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->commission = get_setting('vendor_commission');
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", $request->shop_name));
            $shop->save();

            auth()->login($user, false);
            if (BusinessSetting::where('type', 'email_verification')->first()->value == 0) {
                $user->email_verified_at = date('Y-m-d H:m:s');
                $user->save();
            } else {
                try {
                    $user->notify(new EmailVerificationNotification());
                } catch (\Throwable $th) {
                    $shop->delete();
                    $user->delete();
                    flash(translate('Seller registration failed. Please try again later.'))->error();
                    return back();
                }
            }

            flash(translate('Your Shop has been created successfully!'))->success();
            return redirect()->route('seller.shop.index');
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function becomeSeller(Request $request)
    {
        try {
            $user = auth()->user();
            // Begin transaction
            DB::beginTransaction();
            $shop = new Shop();
            $shop->user_id = $user->id;
            $shop->name = isset($request->shop_name) ? $request->shop_name : $user->name;
            $shop->commission = get_setting('vendor_commission');
            $shop->slug = preg_replace('/\s+/', '-', str_replace("/", " ", (isset($request->shop_name) ? $request->shop_name : $user->name)));
            $shop->save();

            $seller = new Seller();
            $seller->user_id = $user->id;
            $seller->verification_status = 0;
            $seller->requested_to_be_seller =1;
            $seller->save();

            // commit transaction
            DB::commit();
            return $seller;
        } catch (\Throwable $th) {
            flash(translate('Something went wrong'))->error();
            DB::rollback();
            return redirect()->back();
        }
    }

    public function submitBecomeSellerRequest(Request $request)
    {
        $user = auth()->user();

        $userAddress = auth()->user()->addresses;
        $defaultShippingAddress = $userAddress->where('set_default', 1)->where('address_type','1')->first();
        $defaultBillingAddress = $userAddress->where('set_default', 1)->where('address_type','2')->first();
        $basicInfo = auth()->user()->name;

        if(!$basicInfo){
            flash(translate('Please fill basic details'))->warning();
            return back();
        }
        if(!$defaultShippingAddress ||!$defaultBillingAddress){
            flash(translate('Please fill address details'))->warning();
            return back();
        }

        $seller = Seller::where('user_id', $user->id)->first();
        if (!$seller && $defaultShippingAddress && $defaultBillingAddress){
            $seller = $this->becomeSeller($request);
            if ($seller) {
                flash(translate('Request submitted successfully'))->success();
                return back();
            } else {
                flash(translate('Something went wrong'))->error();
                return back();
            }
        } else {
            flash(translate('Seller request already exists'))->warning();
            return back();
        }
    }
}
