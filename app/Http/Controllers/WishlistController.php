<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return 'Page under development';
        $verified_sellers = verified_sellers_id();
        $watchlists = Wishlist::where('user_id', Auth::user()->id)
            ->whereIn("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    ->where('approved', '1')->where('published', 1)
                    ->when(!addon_is_activated('wholesale'), function ($q1) {
                        $q1->where('wholesale_product', 0);
                    })
                    ->when(addon_is_activated('auction'), function ($q2) {
                        $q2->where('auction_product', 0);
                    })
                    ->when(get_setting('vendor_system_activation') == 0, function ($q3) {
                        $q3->where('added_by', 'admin');
                    })
                    ->when(get_setting('vendor_system_activation') == 1, function ($q4) use ($verified_sellers) {
                        $q4->where(function ($p1) use ($verified_sellers) {
                            $p1->where('added_by', 'admin')->orWhere(function ($p2) use ($verified_sellers) {
                                $p2->whereIn('user_id', $verified_sellers);
                            });
                        });
                    });
            })->paginate(15);


            $auction_products_count = Wishlist::where('user_id', Auth::user()->id)
            ->whereIN("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    ->where('auction_end_date', '>=', now())
                    ->where('approved', 1)
                    ->where('published', 1)
                    ->where('auction_product', 1); // Filter for non-auction products
            })->count();

             $non_auction_products_count = Wishlist::where('user_id', Auth::user()->id)
            ->whereIN("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    ->where('approved', 1)
                    ->where('published', 1)
                    ->where('auction_product', 0); // Filter for non-auction products
            })->count();

        return view('frontend.xt-user.xt_view_watchedlist', compact('watchlists','auction_products_count','non_auction_products_count'));
    }
    public function getWatchlistAuctionProducts()
    {   
        $verified_sellers = verified_sellers_id();
        $watchlists = Wishlist::where('user_id', Auth::user()->id)
            ->whereIn("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    // ->where('auction_end_date', '>=', strtotime("now"))
                    ->where('approved', '1')->where('published', 1)
                    ->when(!addon_is_activated('wholesale'), function ($q1) {
                        $q1->where('wholesale_product', 0);
                    })
                    ->when(addon_is_activated('auction'), function ($q2) {
                        $q2->where('auction_product', 1);
                    })
                    ->when(get_setting('vendor_system_activation') == 0, function ($q3) {
                        $q3->where('added_by', 'admin');
                    })
                    ->when(get_setting('vendor_system_activation') == 1, function ($q4) use ($verified_sellers) {
                        $q4->where(function ($p1) use ($verified_sellers) {
                            $p1->where('added_by', 'admin')->orWhere(function ($p2) use ($verified_sellers) {
                                $p2->whereIn('user_id', $verified_sellers);
                            });
                        });
                    })
                    ;
            })->paginate(15);



            $auction_products_count = Wishlist::where('user_id', Auth::user()->id)
            ->whereIN("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    ->where('auction_end_date', '>=', now())
                    ->where('approved', 1)
                    ->where('published', 1)
                    ->where('auction_product', 1); // Filter for non-auction products
            })->count();

             $non_auction_products_count = Wishlist::where('user_id', Auth::user()->id)
            ->whereIN("product_id", function ($query) use ($verified_sellers) {
                $query->select('id')
                    ->from('products')
                    ->where('approved', 1)
                    ->where('published', 1)
                    ->where('auction_product', 0); // Filter for non-auction products
            })->count();



        return view('frontend.xt-user.xt_view_watchedlist', compact('watchlists','auction_products_count','non_auction_products_count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $wishlist = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
            if ($wishlist == null) {
                $wishlist = new Wishlist;
                $wishlist->user_id = Auth::user()->id;
                $wishlist->product_id = $request->id;
                $wishlist->save();
            }else{
                if(Wishlist::destroy($wishlist->id)){
                    return 1;
                }
                return 2;
            }
            return view('frontend.' . get_setting('homepage_select') . '.partials.wishlist');
        }
        return 0;
    }

    public function remove(Request $request)
    {
        $wishlist = Wishlist::findOrFail($request->id);
        if ($wishlist != null) {
            if (Wishlist::destroy($request->id)) {
                return view('frontend.' . get_setting('homepage_select') . '.partials.wishlist');
            }
        }
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
