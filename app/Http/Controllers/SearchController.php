<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Search;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Shop;
use App\Models\Attribute;
use App\Models\AttributeCategory;
use App\Utility\CategoryUtility;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $view = 'frontend.' . get_setting('homepage_select') . '.market-place';

        if (!empty($request->search)) return search($request, searchForAuction: false, view: $view);

        $products = Product::where("auction_product", 0)->orderBy('id', 'desc');

        switch ($request->type) {
            case 'featured':
                $products = $products->filterFeatured(true);
                break;
            case 'todays_deal':
                $products = $products->filterTodayDeal();
                break;
            case 'num_of_sale':
                $products = $products->orderBy("num_of_sale", "desc");
                break;
        }

        $this->store($request);

        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        return view($view, compact('products'));
    }

    public function listing(Request $request)
    {
        return $this->index($request);
    }

    public function listingByCategory(Request $request, Category $category)
    {
        $view = 'frontend.' . get_setting('homepage_select') . '.market-place';

        if ($request->has("search")) return search($request, searchForAuction: false, view: $view,auctiontype:'');

        $category_ids = $category->getIdsWithChildrens();
        $products = Product::whereIn("category_id", $category->getIdsWithChildrens());
        $products = filter_products($products)->with('taxes')->paginate(24)->appends(request()->query());
        return view($view, compact('products',"category_ids"));
    }
    public function auctionListingByCategory(Request $request, Category $category)
    {

        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_auction_products';
        // dd($view);
        if ($request->has("search")) return search($request, searchForAuction: True, view: $view,auctiontype:'');

        $category_ids = $category->getIdsWithChildrens();
        $products = Product::where("auction_product", 1)->whereIn("category_id", $category->getIdsWithChildrens());
        $products = filter_products($products, forAuction: 1,auctiontype:'')->with('taxes')->paginate(24)->appends(request()->query());
        // dd($products);
        return view($view, compact('products', "category_ids"));
    }

    public function upcomingAuctionListingByCategory(Request $request, Category $category)
    {
        $view = 'auction.frontend.' . get_setting('homepage_select') . '.all_upcoming_auction_products';

        if ($request->has("search") || $request->has('keywords')) return search($request, searchForAuction: True, view: $view,auctiontype:"upcoming");

        $category_ids = $category->getIdsWithChildrens();
        //upcoming products
        $currentTime = strtotime("now");
        $products = Product::where("auction_product", 1)->where('auction_start_date', '>', $currentTime)->whereIn("category_id", $category->getIdsWithChildrens());
        $products = filter_products($products, forAuction: 1,auctiontype:'upcoming')->with('taxes')->paginate(24)->appends(request()->query());
        return view($view, compact('products', "category_ids"));
    }

    public function listingByBrand(Request $request, $brand_slug)
    {
        $brand = Brand::where('slug', $brand_slug)->first();
        $request->merge(["search" => "", "brand_ids" => [$brand->id]]);

        if ($brand == null) abort(404);

        return $this->index($request);
    }

    public function bestSelling(Request $request)
    {
        $request->merge(["type" => "num_of_sale"]);
        return $this->index($request);
    }

    public function listingByCollectionOfAuction(Request $request, $auction_number)
    {   
        try{
            // $request->merge(["search" => "", "auction_number" => [decrypt($auction_number)],"sort_by" => "oldest"]);

            $request->merge([
                'search' => '',
                'auction_number' => [decrypt($auction_number)],
                'sort_by' => 'lot',
            ]);
            $view = 'auction.frontend.all_auction_collection_product';
            if ($request->has("search")) {
                return search($request, searchForAuction: true, view: $view,collected:false,auctiontype:'');
            }
        }catch(\Exception $e){
            abort(404);
        }
    }

    //Suggestional Search
    public function ajax_search(Request $request)
    {
        $keywords = array();
        $query = $request->search;
        $products = Product::where('published', 1)->where('tags', 'like', '%' . $query . '%')->get();
        foreach ($products as $key => $product) {
            foreach (explode(',', $product->tags) as $key => $tag) {
                if (stripos($tag, $query) !== false) {
                    if (sizeof($keywords) > 5) {
                        break;
                    } else {
                        if (!in_array(strtolower($tag), $keywords)) {
                            array_push($keywords, strtolower($tag));
                        }
                    }
                }
            }
        }

        $products_query = filter_products(Product::query());

        $products_query = $products_query->where('published', 1)
            ->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('product_translations', function ($q) use ($word) {
                            $q->where('name', 'like', '%' . $word . '%');
                        })
                        ->orWhereHas('stocks', function ($q) use ($word) {
                            $q->where('sku', 'like', '%' . $word . '%');
                        });
                }
            });
        $case1 = $query . '%';
        $case2 = '%' . $query . '%';

        $products_query->orderByRaw("CASE
                WHEN name LIKE '$case1' THEN 1
                WHEN name LIKE '$case2' THEN 2
                ELSE 3
                END");
        $products = $products_query->limit(3)->get();

        $categories = Category::where('name', 'like', '%' . $query . '%')->get()->take(3);

        if (sizeof($keywords) > 0 || sizeof($categories) > 0 || sizeof($products) > 0) {
            return view('frontend.' . get_setting('homepage_select') . '.partials.search_content', compact('products', 'categories', 'keywords'));
        }
        return '0';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->keyword == null) return;
        $search = Search::where('query', $request->keyword)->first();
        if ($search != null) {
            $search->count = $search->count + 1;
            $search->save();
        } else if ($request->keyword !== null) {
            $search = new Search;
            $search->query = $request->keyword;
            $search->save();
        }
    }
}
