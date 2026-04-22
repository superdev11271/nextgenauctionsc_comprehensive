@php
$cart_added = [];
@endphp
@php
$route_ame = $product->auction_product == 1 ? 'auction-product' : 'product';
$product_url = route("$route_ame", $product->slug);
@endphp

@php
$availability = false;
$qty = 0;
if ($product->variant_product) {
foreach ($product->stocks as $key => $stock) {
$qty += $stock->qty;
}
} else {
$qty = optional($product->stocks->first())->qty;
}
if ($qty > 0) {
$availability = true;
}

$currentTime = strtotime('now');
$isUpcoming = $product->auction_start_date > $currentTime;
$isOngoing = $product->auction_start_date <= $currentTime && $product->auction_end_date >= $currentTime; // Ongoing auction
    @endphp

    @php
    $cardClass = 'shop-block-one f';

    $myBid = Auth::user()
    ?->product_bids->where('product_id', $product->id)
    ->first();
    $highest_bid = $product->bids->max('amount');
    if ($myBid) {
    if ($myBid->amount == $highest_bid) {
    $cardClass .= ' highest-bid';
    } else {
    $cardClass .= ' outbid';
    }
    }
    @endphp


    <style>
        .highest-bid .inner-box {
            border: 2px solid #28a745;
            /* Green */
            box-shadow: 0 0 10px #28a745;
        }

        .outbid .inner-box {
            border: 2px solid #dc3545;
            /* Red */
            box-shadow: 0 0 10px #dc3545;
        }
    </style>

    <div class="col-lg-3 col-md-6 col-sm-12">
        <div class="{{ $cardClass }}">
            <div class="inner-box">
                <figure class="image-box">
                    @if ($product->auction_product == 1)
                    @if($isOngoing)
                    <span class="runing text-dark" style="right:38px;">Live</span>
                    @elseif($isUpcoming)
                    <span class="upcoming text-dark" style="right:38px;">Soon</span>
                    @else
                    <span class="upcoming text-dark" style="right:38px;">end</span>
                    @endif
                    @endif
                    <a href="{{ $product_url }}">
                        @if ($product->variant_product)
                        {{-- @if ($product->auction_product == 0)
                            @if (get_setting('marketplace_product_expiry') && $product->auction_end_date)
                                <span class="runing text-dark">
                                        Expire
                                    <span class="countdown-timer"
                                        data-id="{{ $product->id }}"
                        data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                        <span id="countdown-display-{{ $product->id }}"></span>
                        </span>
                        </span>

                        @endif
                        @endif --}}

                        <img src="{{ uploaded_asset($product->stocks->first()->image, 'thumbnail') }}"
                            alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                        @else
                        {{-- @if ($product->auction_product == 0)
                            @if (get_setting('marketplace_product_expiry') && $product->auction_end_date)
                                <span class="runing text-dark">Expire
                                    {{ \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->auction_end_date)) > 0
                                        ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->auction_end_date)) . ' days'
                                        : \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($product->auction_end_date)) . ' hours' }}
                        </span>
                        @endif
                        @endif --}}
                        <img src="{{ get_image($product->thumbnail, 'thumbnail') }}"
                            alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                        @endif
                    </a>
                    <ul class="info-list clearfix">
                        @if ($product->auction_product == 1)
                        <li><a href="javascript:void(0)" class="{{ isWishlisted($product->id) ? 'theme-button-bg' : '' }}" onclick="addToWishList({{ $product->id }})"><i
                                    class="fa-regular watchitem{{ $product->id }} {{ isWishlisted($product->id) ? 'fa-solid' : '' }} fa-heart"
                                    data-toggle="tooltip"
                                    data-title="{{ isWishlisted($product->id) ? translate('Added in watchlist') : translate('Add to watchlist') }}"
                                    data-placement="top" id="watchitem{{ $product->id }}"></i></a></li>
                        @endif
                        @if ($product->auction_product == 0)
                        <li><a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"><i
                                    class="fa-regular watchitem{{ $product->id }} {{ isWishlisted($product->id) ? 'fa-solid' : '' }} fa-heart"
                                    data-toggle="tooltip"
                                    data-title="{{ isWishlisted($product->id) ? translate('Added in watchlist') : translate('Add to watchlist') }}"
                                    data-placement="top" id="watchitem{{ $product->id }}"></i></a></li>
                        @if ($availability)
                        <li>
                            <a href="{{ $product_url }}" data-toggle="tooltip"
                                data-title="{{ translate('Add to Cart') }}"
                                data-placement="top">{{ translate('Add to Cart') }}</a>
                        </li>
                        @else
                        <li>
                            <a href="{{ $product_url }}">{{ translate('Out of Stock') }}</a>
                        </li>
                        @endif
                        <li> <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})"
                                data-toggle="tooltip"
                                data-title="{{ isCompare($product->id) ? translate('Added in compare') : translate('Add to compare') }}"
                                data-placement="top">
                                <i id="compare-{{ $product->id }}"
                                    class="fa-sharp fa-solid fa-code-compare  compare-{{ $product->id }} {{ isCompare($product->id) ? 'text-danger' : '' }}"></i>
                            </a></li>
                        @endif
                    </ul>
                </figure>
                @php
                $highest_bid = $product->bids->max('amount');
                @endphp
                @if ($product->auction_product == 1)

                @if ($product->reserved_price == null)
                <div class="reserve-price-type bg-color-green">
                    <i class="fas fa-check"></i>Not
                    Reserved
                </div>
                @elseif ($highest_bid < $product->reserved_price)
                    <div class="reserve-price-type bg-color-red">
                        <i class="fas fa-close"></i></span>Reserved Not
                        Met
                    </div>
                    @else
                    <div class="reserve-price-type bg-color-green">
                        <i class="fas fa-check"></i>Reserved Met
                    </div>
                    @endif

                    @endif



                    <div
                        class="lower-content auctionCardBottomBox  @if ($product->auction_product == 1 && Auth::check()) auction_card @endif @if ($product->auction_product == 1 && !Auth::check()) not_login_auction_card @endif ">
                        @if ($product->auction_product == 1)
                        <div class="d-flex justify-content-between">
                            <h6 class="pt-1 pb-2 text-truncate">{{ $product->getCollectionLabel() }}</h6>
                            <h6 class="pt-1 pb-2">Lot No: {{ $product->lot }}</h6>
                        </div>
                        @endif
                        <div class="m-1 text-justify">
                            <p class="mb-0 h-45px body-color text-truncate-2 ">
                                <a class="fs-16 head-color mb-0 fw-700" href="{{ $product_url }}" data-toggle="tooltip"
                                    title="{{ $product->getTranslation('name') }}">
                                    {{ Str::limit($product->getTranslation('name'), 25) }}</a>

                            </p>
                        </div>

                        @if ($product->auction_product == 1)

                        @if ($isOngoing)
                        @php
                        $carts = get_user_cart();
                        if (count($carts) > 0) {
                        $cart_added = $carts->pluck('product_id')->toArray();
                        }
                        $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                        @endphp

                        {{-- Ongoing Counter --}}
                        <div class="d-flex flex-column align-items-center pb-2" id="timer">

                            <div class="auction-timer text-center auction-status-text auction-timer-{{ $product->id }} mb-1"
                                data-end="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}"
                                data-endunixtime="{{ $product->auction_end_date }}">
                                <!-- Time Left: <span class="countdown">2d 6h 7m 48s</span> -->
                            </div>
                            <div class="text-white large">
                                <span class="end-date">
                                    {{ \Carbon\Carbon::createFromTimestamp($product->auction_end_date)->setTimezone(config('app.timezone'))->format('l jS F Y') }}
                                </span>
                            </div>
                        </div>

                        @if ($product->auction_end_date > strtotime('now'))
                        <div class="pb-2">
                            @if (Auth::check() && $product->user_id == Auth::user()->id)
                            <div class="py-2 rounded text-center text-wrap bg-secondary">
                                <div class="fs-18 fw-600">
                                    {{ translate('Seller cannot Place Bid to His Own Product') }}
                                </div>
                            </div>
                            @elseif (Auth::check() && Auth::user()->user_type == 'admin')
                            <div class="py-2 rounded text-center text-wrap bg-secondary">
                                <div class="fs-18 fw-600">
                                    {{ translate("Admin cannot Place Bid  to Sellers' Product.") }}
                                </div>
                            </div>
                            @else
                            @if (Auth::check())
                            @php $autobidRange = Auth::user()->bid_where_productId($product->id)?->autobid_amount @endphp
                            {{-- @dump($autobidRange, $highest_bid, $highest_bid == null) --}}
                            @if ($highest_bid != null && $autobidRange > $highest_bid)
                            <div class="bid-aamount pb-2">
                                Autobid set Upto: {{ single_price($autobidRange) }}
                            </div>
                            @else
                            <!-- <div class="bid-aamount pb-2">
                                                BID {{ single_price($product->starting_bid) }}
                                            </div> -->
                            <div class="py-2 text-center">
                                or place a maximum bid
                            </div>
                            <form class="bid-form " data-product-id="{{ $product->id }}"
                                onsubmit="event.preventDefault()"
                                data-min-bid-amount="{{ $min_bid_amount }}"
                                action="{{ route('auction_product_bids.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex gap-2 pb-2">
                                    <input type="hidden" name="product_id"
                                        value="{{ $product->id }}">
                                    <div>
                                        <!-- <input type="text" name="amount"
                                            class="form-control form-control-sm"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                            placeholder="$ 000" name="amount"
                                            id="amountInput{{ $product->id }}"
                                            value="{{ $min_bid_amount + get_next_bid_amount($min_bid_amount) }}" /> -->
                                            <input type="text" name="amount"
                                            class="form-control form-control-sm"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                            placeholder="$ 000" name="amount"
                                            id="amountInput{{ $product->id }}"
                                            value="{{ $min_bid_amount }}" />
                                        {{-- <input type="number" class="form-control form-control-sm"
                                                    id="amountInput{{ $product->id }}" name="amount"
                                        min="{{ $min_bid_amount }}" placeholder="$ 000"> --}}
                                    </div>
                                    <div id="bidbutton{{ $product->id }}">
                                        <button type="button" class="theme-btn-card btn-sm w-100"
                                            onclick="checkMinBidAmount({{ $product->id }},{{ $min_bid_amount }})">
                                            <span id="placeBidText{{ $product->id }}">Place Maximum
                                                Bid</span>
                                            <span id="processingText{{ $product->id }}"
                                                style="display: none;">
                                                <span class="spinner-border" role="status"
                                                    aria-hidden="true"></span> Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            @endif

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
                                            <span style="font-size:16px">
                                                <i id="my-bid-status-{{$product->id}}"
                                                    class="fa fa-thumbs-{{ $highest_bid > $myBid->amount ? 'down text-danger' : 'up text-success' }}  "
                                                    aria-hidden="true" data-toggle="tooltip" title="{{ $highest_bid > $myBid->amount ? 'Someone has placed a higher bid than yours' : 'Your bid is the highest bid at the current time. ' }}"></i>
                                            </span>
                                            <span style="font-size:16px">{{ translate('My Bid') }}:</span>
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
                        <!-- <div class="d-flex flex-wrap gap-2 justify-content-between pb-2">
                            <div class="fw-600">Estimate</div>
                            <div class="price">
                                @if (!empty($product->estimate_start) && $product->estimate_start > 0)
                                    {{ single_price($product->estimate_start) }}-{{ single_price($product->estimate_end) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div> -->


                        <div class="d-flex gap-2 justify-content-between pb-2">
                            <div class="fw-600">Current bid</div>
                            <div class="price" id="currentBidAmount{{ $product->id }}">
                                @if (!empty($highest_bid))
                                {{ single_price($highest_bid) }}
                                @else
                                {{ $min_bid_amount }}
                                @endif
                            </div>
                        </div>
                        @else
                        {{-- Upcoming Counter --}}
                        <div class="d-flex gap-2 pb-2" id="timer">
                            <span class="auction-timer auction-status-text  auction-timer-{{ $product->id }}"
                                data-start="{{ date('Y/m/d H:i:s', $product->auction_start_date) }}"
                                data-startunixtime="{{  $product->auction_start_date}}"
                                data-end="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}"
                                data-endunixtime="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}">
                                {{ date('Y/m/d H:i:s', $product->auction_start_date) }}
                            </span>
                        </div>
                         <div class="text-white large">
                                <span class="end-date">
                                    {{ \Carbon\Carbon::createFromTimestamp($product->auction_start_date)->setTimezone(config('app.timezone'))->format('l jS F Y') }}
                                </span>
                            </div>


                        <!-- <div class="d-flex flex-wrap gap-2 justify-content-between pb-2">
                            <div class="fw-600">Estimate</div>
                            <div class="price">
                                @if (!empty($product->estimate_start) && $product->estimate_start > 0)
                                    {{ single_price($product->estimate_start) }}-{{ single_price($product->estimate_end) }}
                                @else
                                    -
                                @endif
                            </div>
                        </div> -->

                        <div class="d-flex gap-2 justify-content-between pb-2">
                            <div class="fw-600">Starting Bid</div>
                            <div class="price">
                                {{ single_price($product->starting_bid) }}
                            </div>
                        </div>

                        <div class="pb-2 lower-content">
                            <a class="theme-btn-card btn-sm w-100 text-uppercase" href="{{ $product_url }}">View Detail</a>
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
