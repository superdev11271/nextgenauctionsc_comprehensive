@php
    $cart_added = [];
@endphp
@php
    $route_ame = $product->auction_product == 1 ? 'auction-product' : 'product';
    $product_url = route("$route_ame", $product->slug);
    $currentTime = strtotime('now');
    $isUpcoming = $product->auction_start_date > $currentTime;
    $isOngoing = $product->auction_start_date <= $currentTime && $product->auction_end_date >= $currentTime; // Ongoing auction

@endphp

    <div class="shop-block-one b">
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
                    <img src="{{ get_image($product->thumbnail) }}" alt="{{ $product->getTranslation('name') }}"
                        title="{{ $product->getTranslation('name') }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                    </a>
                    <ul class="info-list clearfix">

                        @if ($product->auction_product == 0)
                            <li><a href="javascript:void(0)" onclick="addToWishList({{ $product->id }})"><i
                                        class="fa-regular {{ isWishlisted($detailedProduct->id) ? 'fa-solid' : '' }} fa-heart"></i></a></li>
                            <li><a href="{{ $product_url }}">{{ translate('Add to Cart') }}</a>
                            </li>
                            <li> <a href="javascript:void(0)" onclick="addToCompare({{ $product->id }})"
                                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}"
                                    data-placement="left">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path id="_9f8e765afedd47ec9e49cea83c37dfea"
                                            data-name="9f8e765afedd47ec9e49cea83c37dfea"
                                            d="M18.037,5.547v.8a.8.8,0,0,1-.8.8H7.221a.4.4,0,0,0-.4.4V9.216a.642.642,0,0,1-1.1.454L2.456,6.4a.643.643,0,0,1,0-.909L5.723,2.227a.642.642,0,0,1,1.1.454V4.342a.4.4,0,0,0,.4.4H17.234a.8.8,0,0,1,.8.8Zm-3.685,4.86a.642.642,0,0,0-1.1.454v1.661a.4.4,0,0,1-.4.4H2.84a.8.8,0,0,0-.8.8v.8a.8.8,0,0,0,.8.8H12.854a.4.4,0,0,1,.4.4V17.4a.642.642,0,0,0,1.1.454l3.267-3.268a.643.643,0,0,0,0-.909Z"
                                            transform="translate(-2.037 -2.038)" fill="#ffff" />
                                    </svg>
                                </a></li>
                        @endif
                    </ul>
                </figure>
            </a>
            <div class="lower-content auctionCardBottomBox auction_card">
                @if (
                    $product->auction_product == 1)
                                    <div class="d-flex justify-content-between">
                                        <h6 class="pt-1 pb-2 text-truncate">{{ $product->getCollectionLabel()}}</h6>
                                        <h6 class="pt-1 pb-2">Lot No: {{ $product->lot}}</h6>
                                    </div>
                @endif
                <h6 class="pb-2 product_name"><a href="{{ $product_url }}">{{ $product->getTranslation('name') }}</a></h6>
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
                        $lastBid = $product->bids->last();

                        $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
                    @endphp


                    {{-- Ongoing Counter --}}
                    <div class="d-flex gap-2 pb-2" id="timer">
                        <div class="fw-600">Time Left:</div>
                        <span class="auction-timer auction-timer-{{ $product->id }}"
                                data-end="{{ date('Y/m/d H:i:s', $product->auction_end_date) }}"
                                data-endunixtime="{{ $product->auction_end_date }}">
                            {{ date('Y/m/d H:i:s', $product->auction_end_date) }}
                        </span>
                    </div>

                    @if ($product->auction_end_date > strtotime('now'))
                        <div class="py-2">
                            @if (Auth::check() && $product->user_id == Auth::user()->id)
                                <span
                                    class="badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                            @elseif (Auth::check() && Auth::user()->user_type == 'admin')
                                <span
                                    class="badge badge-inline badge-danger">{{ translate("Admin cannot Place Bid  to Sellers' Product.") }}</span>
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
                                                    value="{{get_next_bid_amount($min_bid_amount,true)}}"
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

                    {{-- <div class="d-flex gap-2 justify-content-between pb-2">
                        <div class="fw-600">Asking bid</div>
                        <div class="price">{{ single_price($product->starting_bid) }}</div>
                    </div> --}}
                @else
                    <div class="d-flex flex-wrap items-items-center">
                        @if (home_base_price($product) != home_discounted_base_price($product))
                            <del class="fw-400 text-secondary me-2">{{ home_base_price($product) }}</del>
                        @endif
                        <!-- price -->
                        <span class="price fw-bold">{{ home_discounted_base_price($product) }}</span>
                    </div>
                @endif
            </div>
        </div>
</div>
