@php
    $cart_added = [];
@endphp
@php
    $route_ame = $product->auction_product == 1 ? 'auction-product' : 'product';
    $product_url = route("$route_ame", $product->slug);

    $currentTime = strtotime('now');
    $isUpcoming = $product->auction_start_date > $currentTime;
    $isOngoing = $product->auction_start_date <= $currentTime && $product->auction_end_date >= $currentTime;
@endphp
<div class="col-lg-4 col-md-6 col-sm-12" id="wishlist_{{ $wishlist_id }}">
    <div class="shop-block-one g">
        <button type="button" onclick="removeFromWishlist({{ $wishlist_id }}, {{  $route_ame =='auction-product' ? 1:0;  }})" class="btn-close" style="z-index: 100;"  data-bs-dismiss="card-compare" aria-label="Close"></button>
        <div class="inner-box">
            <figure class="image-box">
                <a href="{{ $product_url }}">
                @if ($product->auction_product == 1)
                    @if($isOngoing)
                        <span class="runing text-dark" style="right:38px;">Live</span>
                    @elseif($isUpcoming)
                    <span class="upcoming text-dark" style="right:38px;">Soon</span>
                    @else
                    <span class="upcoming text-dark" style="right:38px;">end</span>
                    @endif
                @endif
                    
                <img

                @if($product->variant_product)
                src="{{uploaded_asset($product->stocks->first()->image, 'thumbnail') }}"
                @else
                src="{{ get_image($product->thumbnail) }}"
                @endif
                alt="{{ $product->getTranslation('name') }}"
                title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                </a>
                <ul class="info-list clearfix">

                    @if ($product->auction_product == 0)
                        <li><a href="{{ $product_url }}">{{ translate('Add to Cart') }}</a>
                        </li>
                    @endif
                </ul>
            </figure>
            <div class="lower-content auctionCardBottomBox @if ($product->auction_product == 1) auction_card @endif">

                <h6 class="pb-2 product_name"><a href="{{ $product_url }}">{{ Str::limit($product->getTranslation('name'),20) }}</a>

                </h6>

                

                @if (
                    $product->auction_product == 1 &&
                        $product->auction_start_date <= strtotime('now') &&
                        $product->auction_end_date >= strtotime('now'))
                    <h6 class="pt-1 pb-2">{{ $product->getTranslation('lot') }}</h6>
                @endif

                @php
                    $currentTime = strtotime('now');
                    $isUpcoming = $product->auction_start_date > $currentTime;
                    $isOngoing = $product->auction_start_date <= $currentTime && $product->auction_end_date >= $currentTime; // Ongoing auction
                @endphp
                
            @if($product->auction_product == 1)
                @if ($isOngoing)
                    <!-- Place Bid -->
                    @php
                        $carts = get_user_cart();
                        if (count($carts) > 0) {
                            $cart_added = $carts->pluck('product_id')->toArray();
                        }
                        $highest_bid = $product->bids->max('amount');
                        $lastBid = $product->bids->last();

                        $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                    @endphp

                    {{-- Ongoing Counter --}}
                    <div class="d-flex gap-2 pb-2" id="timer">
                        <span class="auction-timer auction-status-text  auction-timer-{{ $product->id }}"
                                data-endunixtime="{{ $product->auction_end_date }}"
                              data-end="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}">
                            {{ date('Y/m/d H:i:s', $product->auction_end_date) }}
                        </span>
                    </div>

                    @if ($product->auction_end_date > strtotime('now'))
                        <div class="pb-2">
                            @if (Auth::check() && $product->user_id == Auth::user()->id)
                                <span
                                    class="py-2 badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                            @elseif (Auth::check() && Auth::user()->user_type == 'admin')
                                <span
                                    class="py-2 badge badge-inline badge-danger">{{ translate("Admin cannot Place Bid  to Sellers' Product.") }}</span>
                            @else
                                @if (Auth::check())
                                    <div class="bid-aamount pb-2">
                                        BID {{ single_price($product->starting_bid) }}
                                    </div>
                                    <div class="py-2 text-center">
                                        or place a maximum bid
                                    </div>

                                    <form class="bid-form " data-product-id="{{ $product->id }}"
                                        data-min-bid-amount="{{ $min_bid_amount }}"
                                        action="{{ route('auction_product_bids.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex gap-2 pb-2">
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <div>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="amountInput{{ $product->id }}" name="amount"
                                                    min="{{ $min_bid_amount }}" placeholder="$ 000">
                                            </div>
                                            <div id="bidbutton{{ $product->id }}">
                                                <button type="button" class="theme-btn-card btn-sm w-100"
                                                    onclick="checkMinBidAmount({{ $product->id }},{{ $min_bid_amount }})">
                                                    <span id="placeBidText{{ $product->id }}">Place Maximum Bid</span>
                                                    <span id="processingText{{ $product->id }}"
                                                        style="display: none;">
                                                        <span class="spinner-border" role="status"
                                                            aria-hidden="true"></span> Processing...
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @if (Auth::check() &&
                                                Auth::user()->product_bids->where('product_id', $product->id)->first() != null)
                                            @php
                                                $myBid = Auth::user()
                                                    ->product_bids->where('product_id', $product->id)
                                                    ->first();
                                            @endphp
                                            <div class="col-md-6 col-lg-12">
                                                <div class="info">
                                                    <div class="d-flex gap-2 justify-content-center fs-5">
                                                        <div class="fw-600 d-flex gap-1 align-items-center">
                                                            <span>
                                                                <i id="my-bid-status"
                                                                    class="fa fa-thumbs-{{ $highest_bid > $myBid->amount ? 'down' : 'up' }}  text-success"
                                                                    aria-hidden="true"></i>
                                                            </span>
                                                            <span>{{ translate('My Bid') }}:</span>
                                                        </div>
                                                        <div class="price" id="mybid{{ $product->id }}">
                                                            @if ($highest_bid != null)
                                                                {{ single_price($myBid->amount) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                @else
                                    <button class="theme-btn-card btn-sm w-100 text-uppercase"
                                        onclick="bid_modal()">Login to bid</button>
                                @endif
                            @endif
                        </div>
                    @endif

                    <div class="d-flex gap-2 justify-content-between pb-2">
                        <div class="fw-600">Auction estimate</div>
                        <div class="price">
                            @if (!empty($product->estimate_start) && $product->estimate_start > 0)
                                {{ single_price($product->estimate_start) }}-{{ single_price($product->estimate_end) }}
                            @else
                                -
                            @endif
                        </div>
                    </div>


                    <div class="d-flex gap-2 justify-content-between pb-2">
                        <div class="fw-600">Current bid</div>
                        <div class="price" id="currentBidAmount{{ $product->id }}">
                            @if (!empty($lastBid->amount))
                                {{ single_price($lastBid->amount) }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-between pb-2">
                        <div class="fw-600">Asking bid</div>
                        <div class="price">{{ single_price($product->starting_bid) }}</div>
                    </div>
                @elseif($isUpcoming)

                    {{-- Upcoming Counter --}}
                    <div class="d-flex gap-2 pb-2" id="timer">
                        <span class="auction-timer auction-status-text  auction-timer-{{ $product->id }}"
                            data-start="{{ date('Y/m/d H:i:s', $product->auction_start_date) }}"
                            data-startunixtime="{{ $product->auction_start_date }}"
                            data-end="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}"
                            data-endunixtime="{{ $product->auction_end_date }}"
                            >
                            {{ date('Y/m/d H:i:s', $product->auction_start_date) }}
                        </span>
                    </div>

                    <div class="d-flex gap-2 justify-content-between pb-2">
                        <div class="fw-600">Auction estimate</div>
                        <div class="price">
                            @if (!empty($product->estimate_start) && $product->estimate_start > 0)
                                {{ single_price($product->estimate_start) }}-{{ single_price($product->estimate_end) }}
                            @else
                                -
                            @endif
                        </div>
                    </div>

                    <div class="pb-2 lower-content">
                        <a class="theme-btn-card btn-sm w-100 text-uppercase" href="{{ $product_url }}">View
                        Auction</a>
                    </div>
                @else
                    <div class="d-flex flex-wrap items-items-center">
                        @if ($product->auction_product == 1)
                            <div class="rounded bg-black text-center p-3 mt-1  col-sm-12 auction-status-text">
                                This Auction is over.
                            </div>
                        @endif

                    </div>
                @endif
            @else
                <div class="lower-content auctionCardBottomBox">
                    <div class="d-flex gap-2">
                        @if ($product->variant_product)
                            <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price') }}</div>
                            <div class="mb-0 fs-14  body-color  mb-2">
                                <del
                                    class="fw-400 opacity-50 mr-1">{{ home_base_price_by_stock_id($product->stocks->first()->id) }}</del>
                                <span
                                    class="fw-700">{{ home_discounted_base_price_by_stock_id($product->stocks->first()->id) }}
                                </span>
                            </div>
                        @else
                            <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price') }}</div>
                            <div class="mb-0 fs-14  body-color  mb-2">
                                @if (home_base_price($product) != home_discounted_base_price($product))
                                    <del class="fw-400 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                @endif
                                <span class="fw-700">{{ home_discounted_base_price($product) }} </span>
                            </div>
                        @endif
                    </div>
                    @if ($product->brand?->name)
                        <div class="d-flex gap-2">
                            <p class="fs-16 head-color mb-0 fw-700">{{ translate('Brand') }}</p>
                            <p class="mb-0 fs-14 body-color  mb-2">
                                {{ $product->brand->name }}
                            </p>
                        </div>
                    @endif
                    @php $category = get_category([$product->category_id]) @endphp
                    @if (isset($category[0]))
                        <div class="d-flex gap-2">
                            <div class="fs-16 head-color mb-0 fw-700">{{ translate('Category') }}</div>
                            <div class="mb-0 fs-14 body-color  mb-2 text-wrap">
                                {{ $category[0]->name }}
                            </div>
                        </div>
                    @endif
                    @if (get_setting('marketplace_product_expiry') && $product->auction_end_date)
                        <div class="d-flex gap-2">
                            <div class="fs-16 head-color mb-0 fw-700">{{ translate('Time Left : ') }}</div>
                            <div class="mb-0 fs-14 body-color  mb-2 text-wrap">
                                <div class="d-flex gap-2 pb-2" style="font-weight: bold; font-size: 15px; color: goldenrod;">
                                    <span class="countdown-timer" 
                                        data-id="{{ $product->id }}"
                                        data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                                        <span id="countdown-display-{{ $product->id }}"></span>
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
