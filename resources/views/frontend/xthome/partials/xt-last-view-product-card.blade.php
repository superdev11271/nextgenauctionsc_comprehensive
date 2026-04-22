
@php

    $cart_added = [];

    $product_url = route('product', $product->slug);
    if ($product->auction_product == 1) {
        $product_url = route('auction-product', $product->slug);
    }

    $availability = false;
    $qty = 0;
    if($product->variant_product) {
        foreach ($product->stocks as $key => $stock) {
            $qty += $stock->qty;
        }
    }
    else {
        $qty = optional($product->stocks->first())->qty;
    }
    if($qty > 0){
        $availability = true;
    }
@endphp

@php  
   $expiry = (\Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->auction_end_date)) > 0) 
                            ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($product->auction_end_date)) . ' days' 
                            : \Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($product->auction_end_date)) . ' hours';
@endphp

<div class="col-lg-12 col-md-12 col-sm-12 h-100">
    <div class="shop-block-one h">
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
                @if($product->variant_product)
                    @if ($product->auction_product == 0)
                        @if( get_setting('marketplace_product_expiry') &&  $product->auction_end_date)
                            <span class="runing text-dark">
                                Expire
                                <span class="countdown-timer" 
                                    data-id="{{ $product->id }}"
                                    data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                                    <span id="countdown-display-{{ $product->id }}"></span>
                                </span>
                            </span>
                        @endif
                    @endif
                <img src="{{ uploaded_asset($product->stocks->first()->image, 'thumbnail') }}" alt="{{ $product->getTranslation('name') }}"
                    title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                @else
                    @if ($product->auction_product == 0)
                        @if( get_setting('marketplace_product_expiry') &&  $product->auction_end_date)
                        <span class="runing text-dark">
                                Expire
                                <span class="countdown-timer" 
                                    data-id="{{ $product->id }}"
                                    data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                                    <span id="countdown-display-{{ $product->id }}"></span>
                                </span>
                        </span>
                        @endif
                    @endif
                <img src="{{ get_image($product->thumbnail, 'thumbnail') }}" alt="{{ $product->getTranslation('name') }}"
                    title="{{ $product->getTranslation('name') }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                @endif
                <ul class="info-list clearfix">

                    @if ($product->auction_product == 0)
                        <li><a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"><i
                                    class="fa-regular watchitem{{ $product->id }} {{ isWishlisted($product->id) ? 'fa-solid' : '' }} fa-heart"
                                    id="watchitem{{ $product->id }}"></i></a></li>

                        @if($availability)
                        <li>
                            <a href="{{ $product_url }}">{{ translate('Add to Cart') }}</a>
                        </li>
                        @else
                        <li>
                            <a href="{{ $product_url }}">{{ translate('Out of Stock') }}</a>
                        </li>
                        @endif
                        <li> <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})"
                                data-toggle="tooltip" data-title="{{ translate('Add to compare') }}"
                                data-placement="left">
                                <i id="compare-{{ $product->id }}" class="fa-sharp fa-solid fa-code-compare  compare-{{ $product->id }} {{ isCompare($product->id) ? 'text-danger' : '' }}"></i>

                            </a></li>
                    @endif


                    @if (
                        $product->auction_product == 1 &&
                            $product->auction_start_date <= strtotime('now') &&
                            $product->auction_end_date >= strtotime('now'))
                        <!-- Place Bid -->
                        @php
                            $carts = get_user_cart();
                            if (count($carts) > 0) {
                                $cart_added = $carts->pluck('product_id')->toArray();
                            }
                            $highest_bid = $product->bids->max('amount');
                            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                        @endphp
                        <li><a href="javascript:void(0);" onclick="bid_modal()">

                                @if (Auth::check() &&
                                        Auth::user()->product_bids->where('product_id', $product->id)->first() != null)
                                    {{ translate('Change Bid') }}
                                @else
                                    {{ translate('Login to Bid') }}
                                @endif
                            </a>
                        </li>
                    @endif



                </ul>
            </figure>
            <div class="lower-content auctionCardBottomBox">
                @if (
                    $product->auction_product == 1 &&
                        $product->auction_start_date <= strtotime('now') &&
                        $product->auction_end_date >= strtotime('now'))
                    <p class="mb-0" style="font-size:14px">{{ date('D d - M - Y H:i', $product->auction_end_date) }}
                    </p>
                @endif
                <a href="{{ $product_url }}" data-toggle="tooltip" title="{{$product->getTranslation('name')}}">{{ Str::limit($product->getTranslation('name'),30) }}</a>
                @if ($product->auction_product == 0)
                <div class="d-flex gap-2">
                    @if($product->variant_product)
                        <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price')}}</div>
                            <div class="mb-0 fs-14  body-color  mb-2">
                            <span id="chosen_price">{{home_discounted_base_price_by_stock_id($stock->id)}} </span> <del class="fw-400 text-secondary me-2"> {{home_base_price_by_stock_id($stock->id) }} </del>
                        </div>
                    @else
                        <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price')}}</div>
                            <div class="mb-0 fs-14  body-color  mb-2">
                                @if(home_base_price($product) != home_discounted_base_price($product))
                                    <del class="fw-400 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                @endif
                            <span class="fw-700">{{ home_discounted_base_price($product) }}</span>
                        </div>
                    @endif
                </div>
                @endif
                @if ($product->auction_product == 1)
                    <span class="price">{{ single_price($product->starting_bid) }}</span>
                @endif
            </div>
            @if ($product->auction_product == 1  && $product->auction_end_date > strtotime('now'))
                <span class="TimerSpan auction-timer"
                    data-date="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}">{{ date('Y/m/d H:i:s', $product->auction_end_date) }}</span>
            @endif
        </div>
    </div>
</div>
