<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Jobs\SendProductReclaimedEmail;
use App\Models\AttributeProduct;
use App\Models\AuctionProductBid;
use App\Models\Brand;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use App\Models\ProductTranslation;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use App\Services\AuctionService;
use App\Models\ProductQuery;
use App\Services\FrequentlyBroughtProductService;
use App\Services\ProductFlashDealService;
use App\Services\ProductService;
use App\Services\ProductStockService;
use App\Services\ProductTaxService;
use Artisan;
use Auth;
use Carbon\Carbon;
use DB;
use Cookie;

use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Log;

class AuctionProductController extends Controller
{
    protected $productService;
    protected $productTaxService;
    protected $productFlashDealService;
    protected $productStockService;
    protected $frequentlyBroughtProductService;

    public function __construct(
        ProductService $productService,
        ProductTaxService $productTaxService,
        ProductFlashDealService $productFlashDealService,
        ProductStockService $productStockService,
        FrequentlyBroughtProductService $frequentlyBroughtProductService
    ) {
        $this->productService = $productService;
        $this->productTaxService = $productTaxService;
        $this->productFlashDealService = $productFlashDealService;
        $this->productStockService = $productStockService;
        $this->frequentlyBroughtProductService = $frequentlyBroughtProductService;

        // Staff Permission Check
        $this->middleware(['permission:view_all_auction_products'])->only('all_auction_product_list');
        $this->middleware(['permission:view_inhouse_auction_products'])->only('inhouse_auction_products');
        $this->middleware(['permission:view_seller_auction_products'])->only('seller_auction_products');
        $this->middleware(['permission:add_auction_product'])->only('product_create_admin');
        $this->middleware(['permission:edit_auction_product'])->only('product_edit_admin');
        $this->middleware(['permission:delete_auction_product'])->only('product_destroy_admin');
        $this->middleware(['permission:view_auction_product_orders'])->only('admin_auction_product_orders');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Auction products list admin panel
    public function all_auction_product_list(Request $request)
    {
        // dd($request->all());
        $sort_search = null;
        $seller_id = null;
        $type = 'all';

        $products = Product::query()
        ->with('bids.user')
        ->where('auction_product', 1)
        ->orderByRaw('CAST(lot AS UNSIGNED) ASC')
        ->orderBy('updated_at', 'desc');

        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $sort_search = $searchTerm;

            $products = $products->where(function ($query) use ($searchTerm) {
                $query->where('auction_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('auction_label', 'like', '%' . $searchTerm . '%')
                    ->orWhere('name', 'like', '%' . $searchTerm . '%') // ✅ ADD THIS
                    ->orWhereHas('bids.user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }
            // dd($request->all());
        if ($request->filled('sold_status')) {
            $products = $products->where('sold_status', $request->sold_status);
        }

           if ($request->filled('auction_filter')) {
                $products = $products->where('auction_number', 'like', '%' . $request->auction_filter . '%');
                }
            $products = $products->paginate(15);
            // $products = $products->get();
            // dd($products);
        return view('auction.auction_products.index', compact('products', 'sort_search', 'type', 'seller_id'));
    }



    public function inhouse_auction_products(Request $request)
    {
        $sort_search = null;
        $seller_id = null;
        $type = 'in_house';
        $products = Product::where('added_by', 'admin')->orderBy('created_at', 'desc')->where('auction_product', 1);
        if ($request->search != null) {
            $products = $products->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('auction.auction_products.index', compact('products', 'sort_search', 'type', 'seller_id'));
    }

    public function seller_auction_products(Request $request)
    {
        $sort_search = null;
        $seller_id = null;
        $type = 'seller';
        $products = Product::where('added_by', 'seller')->orderBy('created_at', 'desc')->where('auction_product', 1);

        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }

        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('auction.auction_products.index', compact('products', 'sort_search', 'type', 'seller_id'));
    }
    // Auction products list admin panel end

    // Auction Products list in Seller panel
    public function auction_product_list_seller(Request $request)
    {
        if (get_setting('seller_auction_product') == 0) {
            return redirect()->route('home');
        }

        $sort_search = null;
        $products = Product::where('auction_product', 1)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('auction.frontend.seller.auction_product_list', compact('products', 'sort_search'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function product_create_admin()
    {

        $lotIndex = generateLotNumber();

        $auctionNumber = generateAuctionNumber();

        $products = Product::select('auction_number')->where('user_id', Auth::user()->id)->onlyNotStartedAuctions()->distinct()->get();

        $auctionProducts = $products->pluck('auction_number')->toArray();

        $firstProductAuctionNumber = Product::whereIn('auction_number', $auctionProducts)->first();

        $lastProduct = Product::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('auction.auction_products.create', compact('categories', 'lotIndex', 'auctionNumber', 'products', 'auctionProducts', 'firstProductAuctionNumber', 'lastProduct'));
    }

    public function product_create_seller()
    {
        $lotIndex = generateLotNumber();

        $auctionNumber = generateAuctionNumber();

        $products = Product::where('user_id', Auth::user()->id)->select('auction_number')->onlyNotStartedAuctions()->distinct()->get();

        $auctionProducts = $products->pluck('auction_number')->toArray();

        $firstProductAuctionNumber = Product::whereIn('auction_number', $auctionProducts)->first();
        $lastProduct = Product::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        $packageInvalidAt = Carbon::parse(Auth::user()->shop?->package_invalid_at);
        $now = Carbon::now();
        $isExpired = $packageInvalidAt < $now;
        $daysRemaining = $now->diffInDays($packageInvalidAt, false);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        if (get_setting('seller_auction_product') == 1) {
            if (addon_is_activated('seller_subscription')) {
                // if (Auth::user()->shop?->seller_package != null &&  $daysRemaining > 0) {
                //     return view('auction.frontend.seller.auction_product_upload', compact('categories', 'lotIndex', 'auctionNumber'));
                // } else if(Auth::user()->shop?->seller_package_id && $daysRemaining > 0) {
                //     return view('auction.frontend.seller.auction_product_upload', compact('categories', 'lotIndex', 'auctionNumber'));
                // }else{
                //     flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
                //     return back();
                // }
                return view('auction.frontend.seller.auction_product_upload', compact('categories', 'lotIndex', 'auctionNumber', 'products', 'auctionProducts', 'firstProductAuctionNumber', 'lastProduct'));
            } else {
                return view('auction.frontend.seller.auction_product_upload', compact('categories', 'lotIndex', 'auctionNumber', 'products', 'auctionProducts', 'firstProductAuctionNumber', 'lastProduct'));
            }
        }
    }

    public function product_store_admin(ProductRequest $request)
    {
        // dd($request->all());
        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }
        $product = (new AuctionService)->store($request);
        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $product->id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $product->id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);
        AttributeProduct::insert($attributeList);
        flash(translate('Product has been inserted successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction.inhouse_products');
    }

    public function prepareAttributes($attributeList, $category_id, $product_id, $ischeckboxValue = false)
    {
        $output = [];
        foreach ($attributeList as $attribute_id => $attrbute_values) {
            if (!isset($attrbute_values['value']))
                continue;
            $output[] = [
                "product_id" => $product_id,
                "category_id" => $category_id,
                "attribute_id" => $attribute_id,
                "attribute_name" => $attrbute_values['fieldname'],
                "value" => $ischeckboxValue ? join(",", $attrbute_values['value']) : $attrbute_values['value'],
                "status" => 1
            ];
        }
        // todo make status part dynamic for edit part
        return $output;
    }

    public function product_store_seller(ProductRequest $request)
    {
        // if (addon_is_activated('seller_subscription')) {
        //     if (
        //         Auth::user()->shop?->seller_package == null ||
        //         Auth::user()->shop?->seller_package->product_upload_limit <= Auth::user()->products()->count()
        //     ) {
        //         flash(translate('Upload limit has been reached. Please upgrade your package.'))->warning();
        //         return back();
        //     }
        // }

        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }
        $product = (new AuctionService)->store($request);
        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $product->id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $product->id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);
        AttributeProduct::insert($attributeList);

        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction_products.seller.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function product_destroy_admin($id)
    {
        $id = decrypt($id);
        (new AuctionService)->destroy($id);

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction.inhouse_products');
    }

    public function product_destroy_seller($id)
    {
        $id = decrypt($id);
        (new AuctionService)->destroy($id);

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction_products.seller.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function product_edit_admin(Request $request, $id)
    {
        $product = Product::findOrFail(decrypt($id));
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        // $products = Product::select('auction_number')->where("user_id", $product->user_id)->onlyNotStartedAuctions()->distinct()->get();
        $products = Product::select('auction_number')
        ->whereNotNull('auction_number')
        ->where('auction_number', '!=', '0')
        ->distinct()
        ->get();
        $auctionCount = Product::where("auction_number", $product->auction_number)->count();
        // dd($auctionCount);
        $auctionCount = 0;
        // dd($products);
        return view('auction.auction_products.edit', compact('product', 'categories', 'tags', 'lang', "products", "auctionCount"));
    }

    public function product_edit_seller(Request $request, $id)
    {
        $id = decrypt($id);
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        $products = Product::select('auction_number')->where("user_id", auth()->id())->onlyAuctionNotOver()->distinct()->get();

        $auctionCount = Product::where("auction_number", $product->auction_number)->count();
        return view('auction.frontend.seller.auction_product_edit', compact('product', 'categories', 'tags', 'lang', "products", "auctionCount"));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function product_update_admin(ProductRequest $request, $id)
    {
        // dd($request->all());
        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }
        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);

        (new AuctionService)->update($request, $id);

        AttributeProduct::where(["product_id" => $id])->delete();
        AttributeProduct::insert($attributeList);

        flash(translate('Product has been Updated successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return back();
    }

    public function product_update_seller(ProductRequest $request, $id)
    {
        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }

        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);

        (new AuctionService)->update($request, $id);
        AttributeProduct::where(["product_id" => $id])->delete();
        AttributeProduct::insert($attributeList);

        flash(translate('Product has been Updated successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return back();
    }

    public function get_products_by_brand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->get();
        return view('partials.product_select', compact('products'));
    }


    public function updatePublished(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $product->published = $request->status;

        if ($product->added_by == 'seller' && addon_is_activated('seller_subscription')) {
            $seller = $product->user?->shop;
            if ($seller->package_invalid_at != null && Carbon::now()->diffInDays(Carbon::parse($seller->package_invalid_at), false) <= 0) {
                return 0;
            }
        }
        $product->save();
        return 1;
    }

    public function all_auction_products(Request $request)
    {
        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_auction_products';
        if (!empty($request->search)) {
            return search($request, true, view: $view);
        }

        $products = get_auction_products(null, 16);

        return view($view, compact('products'));
    }

    public function all_upcoming_auction_products(Request $request)
    {
        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_upcoming_auction_products';

        if (!empty($request->search)) return search($request, true, view: $view, auctiontype: 'upcoming');

        $products = get_upcoming_auction_products(null, 16);

        return view($view, compact('products'));
    }

    public function auction_product_details(Request $request, $slug)
    {

        $detailedProduct  = Product::where('slug', $slug)->with("attrs")->first();
        $isInWishlist = Auth::check() ?  in_array($detailedProduct?->id, auth()->user()->wishlists()->pluck("product_id")->toArray()) : $isInWishlist = null;
        if ($detailedProduct != null) {
            $product_queries = ProductQuery::where('product_id', $detailedProduct->id)->where('customer_id', '!=', Auth::id())->latest('id')->paginate(3);
            $total_query = ProductQuery::where('product_id', $detailedProduct->id)->count();
            $reviews = $detailedProduct->reviews()->paginate(3);
            $bid_count = AuctionProductBid::with(['product'])->where('product_id', $detailedProduct->id)->count();
            $bid_list = AuctionProductBid::with(['product', 'user'])->orderBy('amount', 'DESC')->where('product_id', $detailedProduct->id)->get();

            // review status
            $review_status = 0;
            if (Auth::check()) {
                $OrderDetail = OrderDetail::with(['order' => function ($q) {
                    $q->where('user_id', Auth::id());
                }])->where('product_id', $detailedProduct->id)->where('delivery_status', 'delivered')->first();
                $review_status = $OrderDetail ? 1 : 0;
            }

            //:: Last View Cockies logic ::
            $lastViewedProducts = json_decode(Cookie::get('last_viewed_auction_products', '[]'), true);

            if (!is_array($lastViewedProducts)) {
                $lastViewedProducts = [];
            }
            $lastViewedProducts[] = $detailedProduct->id;
            // Trim the array to contain only the last 10 viewed products
            $lastViewedProducts = array_slice($lastViewedProducts, -10);
            Cookie::queue('last_viewed_auction_products', json_encode($lastViewedProducts), 43200);

            return view('auction.frontend.' . get_setting('homepage_select')  . '.auction_product_details', compact('detailedProduct', 'product_queries', 'total_query', 'reviews', 'review_status', 'bid_count', 'bid_list'));
        }
        abort(404);
    }

    public function purchase_history_user()
    {
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('orders.user_id', Auth::user()->id)
            ->where('products.auction_product', '1')
            ->select('order_details.order_id as id')
            ->paginate(15);
        return view('auction.frontend.xthome.purchase_history', compact('orders'));
    }

    public function admin_auction_product_orders(Request $request)
    {
        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $date = $request->date;
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('products.auction_product', '1')
            ->select('orders.id');

        if ($request->payment_status != null) {
            $orders = $orders->where('payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if ($request->delivery_status != null) {
            $orders = $orders->where('delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('code', 'like', '%' . $sort_search . '%');
        }
        if ($date != null) {
            $orders = $orders->whereDate('orders.created_at', '>=', date('Y-m-d', strtotime(explode(" to ", $date)[0])))->whereDate('orders.created_at', '<=', date('Y-m-d', strtotime(explode(" to ", $date)[1])));
        }

        $orders = $orders->paginate(15);

        return view('auction.auction_product_orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search', 'date'));
    }

    public function auction_orders_show($id)
    {
        $order = Order::findOrFail(decrypt($id));
        $order_shipping_address = json_decode($order->shipping_address);
        $delivery_boys = User::where('city', $order_shipping_address->city)
            ->where('user_type', 'delivery_boy')
            ->get();
        $order->viewed = 1;
        $order->save();
        return view('auction.auction_product_order_details', compact('order', 'delivery_boys'));
    }

    public function seller_auction_product_orders(Request $request)
    {
        if (get_setting('seller_auction_product') == 0) {
            return redirect()->route('home');
        }

        $payment_status = null;
        $delivery_status = null;
        $sort_search = null;
        $orders = DB::table('orders')
            ->orderBy('code', 'desc')
            ->where('orders.seller_id', Auth::user()->id)
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->where('products.auction_product', '1')
            ->select('orders.id');

        if (!empty($request->payment_status)) {
            $orders = $orders->where('orders.payment_status', $request->payment_status);
            $payment_status = $request->payment_status;
        }
        if (!empty($request->delivery_status)) {
            $orders = $orders->where('orders.delivery_status', $request->delivery_status);
            $delivery_status = $request->delivery_status;
        }
        if ($request->has('search')) {
            $sort_search = $request->search;
            $orders = $orders->where('orders.code', 'like', '%' . $sort_search . '%');
        }

        $orders = $orders->paginate(15);
        return view('auction.frontend.seller.auction_product_orders', compact('orders', 'payment_status', 'delivery_status', 'sort_search'));
    }
    public function get_highest_bid(Product $product)
    {
        return $product->bids->max('amount');
    }
    public function validateAttributes($field)
    {
        foreach ($field as $attributeId => $data) {
            if ($data['isrequired'] == 1 && !isset($data["value"])) {
                return true;
            }
        }
        return false;
    }



    public function auction_bid_product_admin(Request $request)
    {

        $products = Product::query()
            ->onlyLiveAuctions()
            ->join('auction_product_bids', 'products.id', '=', 'auction_product_bids.product_id')
            ->where('products.auction_product', '1')
            ->get();
        return view('auction.auction_products.current_bid_product.current_bid_product_admin', compact('products'));
    }

    public function auction_bid_product_seller(Request $request)
    {
        if (get_setting('seller_auction_product') == 0) {
            return redirect()->route('home');
        }

        $sort_search = null;
        // $products = Product::query()
        //     ->onlyLiveAuctions()
        //     ->join('auction_product_bids', 'products.id', '=', 'auction_product_bids.product_id')
        //     ->where('products.auction_product', '1')
        //     ->where('products.user_id', Auth::user()->id);

        $products = Product::query()->onlyLiveAuctions()->where('auction_product', 1)->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');

        if ($request->search != null) {
            $products = $products
                ->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }

        $products = $products->paginate(15);

        return view('auction.frontend.seller.current_bid_product', compact('products', 'sort_search'));
    }

//    public function bulk_relist_auction_store(Request $request)
// {
//     // If using AJAX debug, you can comment out this line after verifying
//     // dd($request->all());


//     $ids = $request->ids;
//     $productIds = explode(',', $request->product_ids); // Get raw product IDs
//     dd($productIds);

//     if (!$ids && empty($productIds)) {
//         return response()->json(['status' => 'error', 'message' => 'No products selected.']);
//     }

//     // Extract auction fields
//     $auctionNumber = $request->auction_number;
//     $auctionLabel = $request->auction_label;
//     // dd($auctionNumber);
//     // Parse date range (assuming it's like "20-07-2025 00:00:00 to 22-07-2025 23:59:00")
//     $dateRange = explode(' to ', $request->auction_date_range);
//      // ✅ Check if auction_date_range is missing or invalid
//     $dateRange = explode(' to ', $request->auction_date_range);
//     if (!isset($dateRange[0]) || empty($dateRange[0])) {
//         flash(translate('Please select products from the same auction only.'))->error();
//         return back();
//     }
//     $startTimestamp = isset($dateRange[0]) ? \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $dateRange[0])->timestamp : null;
//     $endTimestamp = isset($dateRange[1]) ? \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $dateRange[1])->timestamp : null;

//     // ✅ Counter for successful relists
//     $relistedCount = 0;
//     $lotNumber = 1;
//     foreach ($productIds as $id) {
//         try {
//             $product = Product::findOrFail($id);

//               // ✅ Delete existing bids for this product
//             $product->bids()->delete();

//             // ✅ Assign formatted lot number (e.g., 01, 02, 03)
//             $product->lot = str_pad($lotNumber, 2, '0', STR_PAD_LEFT);
//             $lotNumber++;

//             // Skip sold or live products
//             if ($product->sold_status === 'sold' || $product->isLive()) {
//                 continue;
//             }

//             // ✅ Existing logic
//             $product->auction_start_date = $startTimestamp ?? now()->addDay()->timestamp;
//             $product->auction_end_date = $endTimestamp ?? now()->addDay()->addDays(7)->timestamp;
//             $product->sold_status = 'relist';
//             $product->published = 1;
//             $product->approved = 1;

//             // ✅ New fields to update
//             $product->auction_number = $auctionNumber;
//             $product->auction_label = $auctionLabel;

//             $product->save();

//             // ✅ Validation (already exists)
//             if ($this->validateAttributes($request->field ?? [])) {
//                 continue;
//             }
//             $relistedCount++;

//             // ✅ Use AuctionService if needed
//             // (new AuctionService)->update($request, $id);
//             //  dd("Product ID: {$product->id} saved with Auction Number: {$product->auction_number}");
//         } catch (\Exception $e) {
//             continue; // Skip if error
//         }
//     }

//         // flash(translate('Product has been created successfully'))->success();
//           flash(translate("{$relistedCount} product(s) have been relisted successfully."))->success();

//         Artisan::call('view:clear');
//         Artisan::call('cache:clear');
//     return redirect()->route('auction.all_products');

// }

public function bulk_relist_auction_store(Request $request)
{
    // dd($request->all());
    $productIds = explode(',', $request->product_ids); // Get product IDs

    if (empty($productIds)) {
        return response()->json(['status' => 'error', 'message' => 'No products selected.']);
    }

    // Extract auction fields
    $auctionNumber = $request->auction_number;
    $auctionLabel = $request->auction_label;

    // Parse auction date range
    $dateRange = explode(' to ', $request->auction_date_range);
    if (!isset($dateRange[0]) || empty($dateRange[0])) {
        flash(translate('Please select products from the same auction only.'))->error();
        return back();
    }

    // Convert date strings to timestamps
    $startTimestamp = \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $dateRange[0])->timestamp;
    $endTimestamp = isset($dateRange[1]) ? \Carbon\Carbon::createFromFormat('d-m-Y H:i:s', $dateRange[1])->timestamp : null;

    $relistedCount = 0;
    $lotNumber = 1;

    foreach ($productIds as $id) {
        try {
            $product = Product::findOrFail($id);

            // ✅ Normalize sold_status and block if sold
            $soldStatus = strtolower(trim($product->sold_status ?? ''));
            if ($soldStatus === 'sold') {
                // Optional: flash message or log why it was skipped
                continue;
            }

            // ✅ Still block if auction is live
            if ($product->isLive()) {
                continue;
            }

            // 🧹 Delete existing bids
            $product->bids()->delete();

            // 🆕 Set lot number: 01, 02, 03...
            $product->lot = str_pad($lotNumber, 2, '0', STR_PAD_LEFT);
            $lotNumber++;

            // 🆕 Update all fields
            $product->auction_start_date = $startTimestamp;
            $product->auction_end_date = $endTimestamp;
            // $product->sold_status = 'relist';
            $product->published = $request->published ?? 0;
            $product->approved = $request->approved ?? 0;
            $product->auction_number = $auctionNumber;
            $product->auction_label = $auctionLabel;

            $product->save();

            if ($this->validateAttributes($request->field ?? [])) {
                continue;
            }

            $relistedCount++;

        } catch (\Exception $e) {
            continue;
        }
    }

    flash(translate("{$relistedCount} product(s) have been relisted successfully."))->success();

    Artisan::call('view:clear');
    Artisan::call('cache:clear');

    return redirect()->route('auction.all_products');
}


public function bulk_relist_auction_product_form(Request $request)
{
    $ids = $request->ids;
    // dd($ids);
    if (!$ids || !is_array($ids)) {
        return response()->json(['status' => 'error', 'message' => 'No products selected.']);
    }

    // 🔓 Decrypt product IDs
    $decryptedIds = [];
    foreach ($ids as $encryptedId) {
        try {
            $decryptedIds[] = decrypt($encryptedId);
        } catch (\Exception $e) {
            continue;
        }
    }

    if (empty($decryptedIds)) {
        return response()->json(['status' => 'error', 'message' => 'Invalid product selection.']);
    }

    // 🧲 Fetch selected products
    $products = Product::whereIn('id', $decryptedIds)->get();
    // dd($products);
    if ($products->isEmpty()) {
        return response()->json(['status' => 'error', 'message' => 'No valid products found.']);
    }

    $hasInvalidProduct = $products->contains(function ($product) {
        return $product->isLive();
    });

    if ($hasInvalidProduct) {
        flash(translate('Please don’t select live or already relisted products.'))->error();
        return back();
    }
    // 🧹 Clean auction numbers to match e.g. AUC 00023
    $cleanedAuctionNumbers = $products->map(function ($product) {
        if (preg_match('/AUC\s*\d+/', $product->auction_number, $matches)) {
            return $matches[0];
        }
        return null;
    })->filter()->unique();

    // ❌ If more than one unique cleaned auction number => error
    if ($cleanedAuctionNumbers->count() > 1) {
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Please select products from the same auction only.'
        // ]);
         flash(translate('Please select products from the same auction only.'))->error();
        return back();
    }


    // ✅ All products belong to same auction
    $product = $products->first();
    $lotIndex = generateLotNumber();
    $auctionNumber = generateAuctionNumber();
    // dd($auctionNumber);
    // Get all distinct cleaned auction numbers for user’s not-started auctions
    $userAuctionNumbers = Product::where('user_id', $product->user_id)
        ->whereNotNull('auction_number')
        ->where('auction_number', '!=', '')
        ->onlyNotStartedAuctions()
        ->select('auction_number')
        ->get();

    $auctionProducts = $userAuctionNumbers->map(function ($product) {
        if (preg_match('/AUC\s*\d+/', $product->auction_number, $matches)) {
            return $matches[0];
        }
        return null;
    })->filter()->unique()->values()->toArray();

    $tags = json_decode($product->tags);
    $lang = $request->lang ?? 'en';

    $categories = Category::where('parent_id', 0)
        ->where('digital', 0)
        ->with('childrenCategories')
        ->get();
    // dd($decryptedIds);
    return view('auction.auction_products.bulk_relist_auction_product', [
        'lotIndex' => $lotIndex,
        'auctionNumber' => $auctionNumber,
        'products' => $userAuctionNumbers,
        'auctionProducts' => $auctionProducts,
        'product' => $product,
        'categories' => $categories,
        'tags' => $tags,
        'lang' => $lang,
        'productIds' => $decryptedIds,
    ]);
}




    public function relist_auction_product_form(Request $request, $id)
    {
        $lotIndex = generateLotNumber();

        $auctionNumber = generateAuctionNumber();



        $product = Product::findOrFail($id);
        $products = Product::where('user_id', Auth::user()->id)->select('auction_number')->onlyNotStartedAuctions()->distinct()->get();
        $auctionProducts = $products->pluck('auction_number')->toArray();
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('auction.auction_products.relist_auction_product', compact('lotIndex', 'auctionNumber', 'products', 'auctionProducts', 'product', 'categories', 'tags', 'lang'));
    }



    public function relist_product_store(Request $request)
    {
        $auction_product = Product::find($request->auction_product_id);
        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }
        if ($auction_product) {
            $auction_product->sold_status = 'relist';
            $auction_product->save();
        }
        $product = (new AuctionService)->store($request);
        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $product->id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $product->id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);
        AttributeProduct::insert($attributeList);
        flash(translate('Product has been created successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction.inhouse_products');
    }

    public function seller_relist_auction_product_form(Request $request, $id)
    {
        $lotIndex = generateLotNumber();

        $auctionNumber = generateAuctionNumber();

        $products = Product::where('user_id', Auth::user()->id)->select('auction_number')->onlyAuctionNotOver()->distinct()->get();

        $auctionProducts = $products->pluck('auction_number')->toArray();
        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('auction.frontend.seller.seller_relist_auction_product', compact('lotIndex', 'auctionNumber', 'products', 'auctionProducts', 'product', 'categories', 'tags', 'lang'));
    }

    public function seller_relist_product_store(Request $request)
    {
        $auction_product = Product::find($request->auction_product_id);
        if ($this->validateAttributes($request->field ?? [])) {
            return back()->withErrors("Attribute is required.")->withInput();
        }
        if ($auction_product) {
            $auction_product->sold_status = 'relist';
            $auction_product->save();
        }
        $product = (new AuctionService)->store($request);
        $attributeList = $request->field ? $this->prepareAttributes($request->field, $request->category_id, $product->id) : [];
        $checkboxAttributeList = $request->checkbox ? $this->prepareAttributes($request->checkbox, $request->category_id, $product->id, true) : [];
        $attributeList = array_merge($attributeList, $checkboxAttributeList);
        AttributeProduct::insert($attributeList);
        flash(translate('Product has been created successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction_products.seller.index');
    }

    public function move_to_marketplace_form(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        // dd($product);
        $lang = $request->lang;
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
        return view('auction.auction_products.move_to_marketplace', compact('product', 'categories', 'tags', 'lang'));
    }

    public function move_to_marketplace_form_seller(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        $lang = $request->lang;
        $units = ProductUnit::all();
        $tags = json_decode($product->tags);
        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

        return view('auction.frontend.seller.move_to_marketplace_seller', compact('product', 'categories', 'tags', 'lang', "units"));
    }

    public function move_to_marketplace_form_store(ProductRequest $request)
    {
        $auction_product = Product::find($request->auction_product_id);
        $product = $this->productService->store($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);
        if ($auction_product) {
            $auction_product->clone_id = $product->id;
            $auction_product->sold_status = 'moved';
            $auction_product->save();
        }
        $product->categories()->attach($request->category_ids);
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }
        $this->productFlashDealService->store($request->only([
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        $this->frequentlyBroughtProductService->store($request->only([
            'product_id',
            'frequently_brought_selection_type',
            'fq_brought_product_ids',
            'fq_brought_product_category_id'
        ]));

        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang',
            'name',
            'unit',
            'description',
            'product_id'
        ]));
        flash(translate('Product has been inserted successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction.all_products');
    }

    public function seller_move_to_marketplace_form_store(ProductRequest $request)
    {
        $auction_product = Product::where('id', $request->auction_product_id)->where('auction_product', '1')->first();
        $product = $this->productService->store($request->except([
            '_token',
            'sku',
            'choice',
            'tax_id',
            'tax',
            'tax_type',
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]));
        $request->merge(['product_id' => $product->id]);
        if ($auction_product) {
            $auction_product->clone_id = $product->id;
            $auction_product->sold_status = 'moved';
            $auction_product->save();
        }
        $product->categories()->attach($request->category_ids);
        if ($request->tax_id) {
            $this->productTaxService->store($request->only([
                'tax_id',
                'tax',
                'tax_type',
                'product_id'
            ]));
        }
        $this->productFlashDealService->store($request->only([
            'flash_deal_id',
            'flash_discount',
            'flash_discount_type'
        ]), $product);

        $this->productStockService->store($request->only([
            'colors_active',
            'colors',
            'choice_no',
            'unit_price',
            'sku',
            'current_stock',
            'product_id'
        ]), $product);

        $this->frequentlyBroughtProductService->store($request->only([
            'product_id',
            'frequently_brought_selection_type',
            'fq_brought_product_ids',
            'fq_brought_product_category_id'
        ]));

        $request->merge(['lang' => env('DEFAULT_LANGUAGE')]);
        ProductTranslation::create($request->only([
            'lang',
            'name',
            'unit',
            'description',
            'product_id'
        ]));
        flash(translate('Product has been inserted successfully'))->success();
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        return redirect()->route('auction_products.seller.index');
    }

    public function updateProductStatus(Request $request, Product $product)
    {
        $product->sold_to = $request->bidder_id;
        $product->sold_status = $request->status;
        $product->save();
    }



    public function product_reclaim($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);
            // Update the sold_status of the product to 'reclaimed'
            $product->update(['sold_status' => 'reclaimed']);
            // Get the list of bids for the product, ordered by amount in descending order
            $bidList = AuctionProductBid::with(['product', 'user'])
                ->orderByDesc('amount')
                ->where('product_id', $id)
                ->get();
            // Get the name of the reclaimed product
            $productName = $product->name;
            if (!empty($bidList)) {
                // Loop through each bid and send a reclaim notification email to the bidder
                foreach ($bidList as $bid) {
                    $userName = $bid->user->name;
                    $userEmail = $bid->user->email;
                    SendProductReclaimedEmail::dispatch($productName, $userEmail, $userName);
                }
            }
            // Flash a success message if the reclaim process is successful
            flash(translate('Product has been reclaimed successfully'))->success();
        } catch (\Exception $e) {
            // Log any exceptions that occur during the reclaim process
            Log::error('Failed to reclaim product: ' . $e->getMessage());
            flash(translate('Failed to reclaim product'))->error();
        }

        if (Auth::user()->user_type == 'seller') {
            return redirect()->route('auction_products.seller.index');
        } else {
            return redirect()->back();
        }

        // Redirect the user back to the seller's index page

    }

    public function checkAuctionNumber(Request $request)
    {
        $auctionNumber = $request->input('auction_number');
        $query = Product::where('auction_number', $auctionNumber)
            ->orderBy('id', 'desc');
        $product = $query->first();
        if ($product == null) return null;

        $start_date = date('d-m-Y H:i:s', $product->auction_start_date);
        $end_date = date('d-m-Y H:i:s', ($product->auction_end_date + get_setting('auction_ending_interval') ?? 60));

        // $date = Carbon::createFromFormat('Y-m-d H:i:s', $end_date);
        // $date->addSeconds(get_setting('auction_ending_interval')?? 60);
        // $final_end_date = $date->format('d-m-Y H:i:s');

        return response()->json(['product' => $product, 'count' => $query->count(), 'selected_date' => $start_date . ' to ' . $end_date]);
    }



    public function auctionCollection(Request $request)
    {

        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_auction_products';

        if ($request->has("search")) return search($request, true, view: $view, collected: true, auctiontype: '');

        $products = get_auction_products(null, 16, true);

        return view($view, compact('products'));
    }


    public function upcomingAuctionCollection(Request $request)
    {
        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_upcoming_auction_products';

        if ($request->has("search")) return search($request, true, view: $view, collected: true, auctiontype: 'upcoming');

        $products = get_upcoming_auction_products(null, 16, true);

        return view($view, compact('products'));
    }
}
