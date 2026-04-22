@extends('frontend.layouts.xt-app')

@section('meta_title'){{ $detailedProduct->meta_title }}@stop
@section('meta_description'){{ $detailedProduct->meta_description }}@stop
@section('meta_keywords'){{ $detailedProduct->tags }}@stop
    @php
        $photos = empty($detailedProduct->photos) ? [] : explode(',', $detailedProduct->photos);
        $thumbnail_img = $detailedProduct->thumbnail_img ?? '';
    @endphp
    @push('css')
        <link href="{{ static_asset('xt-assets/css/product.css') }}" rel="stylesheet">
        <link href="{{ static_asset('xt-assets/css/magnific.css') }}" rel="stylesheet">
        <style>
            .read-more-btn {
                background-color: #15171a;  /* Bootstrap Primary Color */
                color: white;
                border: none;
                border-radius: 25px;
                padding: 8px 20px;
                font-size: 16px;
                transition: background-color 0.3s, transform 0.2s;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            }

            .read-more-btn:hover {
                background-color: #1a1e24;
                transform: scale(1.05);
            }

            .read-more-btn:active {
                transform: scale(0.95);
            }

            .read-more-btn:focus {
                outline: none;
                box-shadow: 0 0 0 4px rgba(49, 50, 50, 0.5);
            }

        </style>
    @endpush
    @push('js')
        <script src="{{ static_asset('xt-assets/js/magnific.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.image-popup').magnificPopup({
                    type: 'image',
                    mainClass: 'mfp-with-zoom',
                    gallery: {
                        enabled: true
                    }
                });
            });
        </script>
    @endpush

@section('content')


    @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp

    @php $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid; @endphp
    <!-- shop-section -->
    <div class="shop-section pb-0 pt-5">

        <section>
            <div class="auto-container wow fadeInUp animated animated animated" data-wow-delay="00ms"
                data-wow-duration="1500ms">

                <div class="row">


                    <div class="col-lg-6 content-area">

                        <div class="insize position-relative">
                            <div class="wishlist_button {{ isWishlisted($detailedProduct->id) ? 'theme-button-bg' : '' }}">
                                <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }})">
                                    <i class="fa-regular  watchitem{{ $detailedProduct->id }} {{ isWishlisted($detailedProduct->id) ? 'text-danger fa-solid' : 'text-danger' }} fa-heart"
                                        data-toggle="tooltip" data-title="{{ translate('Add to Watchlist') }}"></i>
                                </a>
                            </div>
                            <div id="sync1" class="owl-carousel owl-theme">
                                <div class="item">
                                    <a class="image-popup" href="{{ uploaded_asset($thumbnail_img) }}">
                                        <img src="{{ uploaded_asset($thumbnail_img) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                    </a>
                                </div>
                                @foreach ($photos as $key => $photo)
                                    <div class="item">
                                        <a class="image-popup" href="{{ uploaded_asset($photo) }}">
                                            <img src="{{ uploaded_asset($photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                        </a>
                                    </div>
                                @endforeach
                                @foreach ($detailedProduct->attrs as $attribute)
                                    @if (in_array($attribute->type(), [0]))
                                        <div class="item">
                                            <a class="image-popup" href="{{ uploaded_asset($attribute->value) }}">
                                                <img src="{{ uploaded_asset($attribute->value) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                            </a>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div id="sync2" class="owl-carousel owl-theme">
                                <div class="item">
                                    <h4>
                                        <img src="{{ uploaded_asset($thumbnail_img) }}"  onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                    </h4>
                                </div>
                                @foreach ($photos as $key => $photo)
                                    <div class="item">
                                        <img src="{{ uploaded_asset($photo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                    </div>
                                @endforeach

                                {{-- Only Images --}}
                                @foreach ($detailedProduct->attrs as $attribute)
                                    @if (in_array($attribute->type(), [0]))
                                        <div class="item">
                                            <img src="{{ uploaded_asset($attribute->value) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="landscape" />
                                        </div>
                                    @endif
                                @endforeach 

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 product-sidebar">
                        {{-- <a href="{{url()->previous()}}" class="btn btn-dark" style="float:right">
                            <span class="fs-18 fw-600">Go Back</span>
                        </a> --}}
                        <div class="product-details relative">
                            @if ($detailedProduct->lots->count() > 1 && !$detailedProduct->isAuctionOver())
                                <a class="position-absolute end-0 top-0 theme-btn-one"
                                    href="{{ route('auction_collection_products.all', ['auction_number' => encrypt($detailedProduct->auction_number)]) }}">Auctions</a>
                                    @endif
                                    <p class="auction_nemre_product">
                                {{ ucfirst($detailedProduct->getCollectionLabel()) }}</p>
                            <h5>{{ ucfirst($detailedProduct->getTranslation('lot')) }}</h5>
                            <h4>{{ ucfirst($detailedProduct->getTranslation('name')) }}</h4>
                            @if ($detailedProduct->auction_product == 1)
                                @if ($detailedProduct->reserved_price == null)
                                    <h6 class="mb-1">Not
                                        Reserved<span class="btn btn-soft-success btn-xs" style="cursor: default"
                                            title="Not Reserved">
                                            <i class="fa fa-exclamation-circle" style="color: #BE800F"></i> </span></h6>
                                @elseif ($highest_bid < $detailedProduct->reserved_price)
                                    <h6 class="mb-1">Reserved Not Met<span class="btn btn-soft-danger btn-xs"
                                            style="cursor: default" title="Reserved Not Met">
                                            <i class="fa fa-exclamation-circle" style="color: #BE800F"></i> </span></h6>
                                @else
                                    <h6 class="mb-1">Reserved
                                        Met<span class="btn btn-soft-success btn-xs" style="cursor: default"
                                            title="Reserved Met">
                                            <i class="fa fa-exclamation-circle" style="color: #BE800F"></i>
                                        </span>
                                    </h6>
                                @endif
                            @endif
                            @if ($detailedProduct->brand)
                                <div class="row no-gutters">
                                    <div class="col-12">{{ translate('Brand') }} :
                                        @if ($detailedProduct->brand != null)
                                            <a href="{{ route('products.brand', $detailedProduct->brand->slug) }}">
                                                {{ $detailedProduct->brand->name }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            @php $category = get_category([$detailedProduct->category_id]) @endphp
                            @if (isset($category[0]))
                                <div class="row no-gutters">
                                    <div class="col-12">{{ translate('Category') }} :
                                        <a href="{{ route('auction.products.category', $category[0]->slug) }}">
                                            {{ $category[0]->name }}
                                        </a>
                                    </div>
                                </div>
                            @endif
                            @php
                                $truncatedDescription = '';
                                $description = $detailedProduct->getTranslation('description');
                                if (!empty($description)) {
                                    $truncatedDescription = substr($description, 0, 100);
                                }
                            @endphp
                            <span>{!! $truncatedDescription !!}</span>


                            @if (!$detailedProduct->isAuctionOver() && $detailedProduct->sold_status == null)
                                <div class="row">
                                    <div class="col-md-12">
                                        @if ($detailedProduct->pdf)
                                            <div class="row no-gutters">
                                                <div class="col-3">{{ translate('Attachment') }} : </div>
                                                <div class="col-9">
                                                    <a href="{{ uploaded_asset($detailedProduct->pdf) }}">View</a>
                                                </div>
                                            </div>
                                        @endif

                                        <p>Current Bid <a href="#!"
                                                onclick="bid_list_modal()">({{ $bid_count }})</a>
                                        </p>
                                    </div>

                                    <div class="row no-gutters">
                                        <div class="col-3">{{ translate('Delivery Method:') }}</div>
                                        <div class="col-9">
                                            <span>
                                                {{getDeliveryType($detailedProduct->id, true)}}
                                            </span>
                                        </div>
                                    </div>
                                    @if(getDeliveryType($detailedProduct->id) == 'pickup_point' )
                                    @if($detailedProduct->pickup_days)
                                    <div class="row no-gutters">
                                        <div class="col-3">{{ translate('Pickup Date') }}</div>
                                        <div class="col-9">
                                            <span>
                                                {{$detailedProduct->pickup_days}}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                        @if($detailedProduct->pickup_time)
                                        <div class="row no-gutters">
                                            <div class="col-3">{{ translate('Pickup Time') }}</div>
                                            <div class="col-9">
                                                <span>
                                                    {{$detailedProduct->pickup_time}}
                                                </span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($detailedProduct->pickup_address)
                                        <div class="row no-gutters">
                                            <div class="col-3">{{ translate('Pickup Address') }}</div>
                                            <div class="col-9">
                                                <span>
                                                    {{$detailedProduct->pickup_address}}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="column_list">
                                            {{-- Excluding Images and Text Area --}}
                                            @foreach ($detailedProduct->attrs as $attribute)
                                                @if (!in_array($attribute->type(), [0, 2]))
                                                    <li><span>{{ $attribute->attribute_name }}:</span>
                                                        {{ $attribute->value }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <ul class="column_list_one_column">
                                            @foreach ($detailedProduct->attrs as $attribute)
                                                @if (in_array($attribute->type(), [2]))
                                                    <li><span>{{ $attribute->attribute_name }} : </span>
                                                        {{ $attribute->value }}
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                @php
                                    $currentTime = strtotime('now');
                                    $isUpcoming = $detailedProduct->auction_start_date > $currentTime;
                                    $isOngoing = $detailedProduct->auction_start_date <= $currentTime && $detailedProduct->auction_end_date >= $currentTime; // Ongoing auction
                                @endphp

                                @if($isUpcoming)

                                <div class="breadcrumb__area">
                                    <div class="timer-container">
                                        <span class="fs-18 fw-600" >Auction will start: </span>
                                        <div class="timer ms-auto">
                                            <div class="d-flex" id="date">
                                                <div class="number">0</div>
                                                <div class="number">0</div>
                                            </div>
                                            <span>Days</span>
                                        </div>


                                        <div class="timer">
                                            <div class="d-flex" id="hour">
                                                <div class="number">0</div>
                                                <div class="number">0</div>
                                            </div>
                                            <span>Hour</span>
                                        </div>

                                        <div class="timer">
                                            <div class="d-flex" id="minute">
                                                <div class="number">0</div>
                                                <div class="number">0</div>
                                            </div>
                                            <span>Minute</span>
                                        </div>

                                        <div class="timer">
                                            <div class="d-flex" id="second">
                                                <div class="number">0</div>
                                                <div class="number">0</div>
                                            </div>
                                            <span>Second</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if (
                                        $detailedProduct->auction_product == 1 &&
                                            $detailedProduct->sold_status == null &&
                                            $detailedProduct->auction_start_date <= strtotime('now') &&
                                            $detailedProduct->auction_end_date >= strtotime('now'))

                                    <div class="breadcrumb__area">
                                        <div class="timer-container">
                                            <span class="fs-18 fw-600"> Time left: </span>
                                            <div class="timer ms-auto">
                                                <div class="d-flex" id="date">
                                                    <div class="number">0</div>
                                                    <div class="number">0</div>
                                                </div>
                                                <span>Days</span>
                                            </div>


                                            <div class="timer">
                                                <div class="d-flex" id="hour">
                                                    <div class="number">0</div>
                                                    <div class="number">0</div>
                                                </div>
                                                <span>Hour</span>
                                            </div>

                                            <div class="timer">
                                                <div class="d-flex" id="minute">
                                                    <div class="number">0</div>
                                                    <div class="number">0</div>
                                                </div>
                                                <span>Minute</span>
                                            </div>

                                            <div class="timer">
                                                <div class="d-flex" id="second">
                                                    <div class="number">0</div>
                                                    <div class="number">0</div>
                                                </div>
                                                <span>Second</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="product-info mt-2 mt-3">
                                        <div class="row">
                                            @php
                                                $carts = get_user_cart();
                                                if (count($carts) > 0) {
                                                    $cart_added = $carts->pluck('product_id')->toArray();
                                                }
                                                $highest_bid = $detailedProduct->bids->max('amount');
                                                $lastBid = $detailedProduct->bids->max('amount');

                                                $min_bid_amount =
                                                    $highest_bid != null ? $highest_bid +1 : $detailedProduct->starting_bid;
                                            @endphp
                                            {{-- <div class="col-md-6">
                                                <div class="info">
                                                    <div class="d-flex gap-1 justify-content-start">
                                                        <div class="fw-600 d-flex gap-1 align-items-center"><i
                                                                class="fa fa-dollar-sign me-1"></i> <span>Asking bid:</span>
                                                        </div>
                                                        <div class="price">
                                                            {{ single_price($detailedProduct->starting_bid) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> --}}

                                            {{-- @if ($highest_bid != null)
                                                <div class="col-md-6">
                                                    <div class="info">
                                                        <div class="d-flex gap-2 justify-content-start">
                                                            <div class="fw-600 d-flex gap-1 align-items-center"><i
                                                                    class="fa fa-gavel  me-1"></i>
                                                                <span>{{ translate('Highest Bid') }}:</span>
                                                            </div>
                                                            <div class="price">
                                                                {{ single_price($highest_bid) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif --}}

                                            <div class="col-md-6">
                                                <div class="info">
                                                    <div class="d-flex gap-2 justify-content-start"
                                                        id="currentBid{{ $detailedProduct->id }}"
                                                        @if (!empty($lastBid)) @else style="display:none !important" @endif>
                                                        <div class="fw-600 d-flex gap-1 align-items-center"><i
                                                                class="fa fa-gavel me-1"> </i> <span>Current bid:</span></div>
                                                        <div class="price" id="currentBidAmount{{ $detailedProduct->id }}">
                                                            @if (!empty($lastBid))
                                                                {{ single_price($lastBid) }}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            @if (!empty($detailedProduct->estimate_start) && $detailedProduct->estimate_start > 0)
                                                <div class="col-md-6">
                                                    <div class="info">
                                                        <div class="d-flex gap-2 justify-content-start">
                                                            <div class="fw-600 d-flex gap-1 align-items-center"><i
                                                                    class="fa fa-gavel  me-1"></i> <span>Estimate</span></div>
                                                            <div class="price">
                                                                ${{ number_format_short($detailedProduct->estimate_start) }}-${{ number_format_short($detailedProduct->estimate_end) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (Auth::check() &&
                                                    Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                                                @php
                                                    $myBid = Auth::user()
                                                        ->product_bids->where('product_id', $detailedProduct->id)
                                                        ->first();
                                                @endphp
                                                <div class="col-md-6 col-lg-12">
                                                    <div class="info">
                                                        <div class="d-flex gap-2 justify-content-center fs-5">
                                                            <div class="fw-600 d-flex gap-1 align-items-center">
                                                                <span>
                                                                    <i id="my-bid-status"
                                                                        class="fa fa-thumbs-{{ $highest_bid > $myBid->amount ? 'down' : 'up' }} "
                                                                        aria-hidden="true" data-toggle="tooltip" title="{{ $highest_bid > $myBid->amount ? 'Someone has placed a higher bid than yours' : 'Your bid is the highest bid at the current time. ' }}"></i>
                                                                </span>
                                                                <span>{{ translate('My Bid') }}:</span>
                                                            </div>
                                                            <div class="price" id="mybid{{ $detailedProduct->id }}">
                                                                @if ($highest_bid != null)
                                                                    {{ single_price($myBid->amount) }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="lower-content auctionCardBottomBox">
                                        <!-- Place Bid -->
                                        @if ($detailedProduct->auction_end_date > strtotime('now'))
                                            <div class="pb-2">
                                                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                                                    <div class="bg-dark p-3 text-center rounded">
                                                        <div class="fs-18 fw-600">
                                                            {{ translate('Seller cannot Place Bid to His Own Product') }}
                                                        </div>
                                                    </div>
                                                @else
                                                    @if (Auth::check())
                                                        @php $autobidRange = Auth::user()->bid_where_productId($detailedProduct->id)?->autobid_amount @endphp
                                                        @if ($highest_bid != null && $autobidRange > $highest_bid)
                                                            {{-- if Autobid is on show this section. --}}
                                                            <div class="autobid-section">
                                                                <div class="auto-close">
                                                                    <a type="button" class=""
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#cancel_autobid">
                                                                        <img src="{{ static_asset('xt-assets/images/icons/close.svg') }}"
                                                                            title="Optout Auto Bid">
                                                                    </a>
                                                                </div>
                                                                <div class="row justify-content-center fs-18">
                                                                    {{-- <div class="col-md-6">
                                                                        <div class="info">
                                                                            <div
                                                                                class="d-flex gap-1 justify-content-start">
                                                                                <div
                                                                                    class="fw-600 d-flex gap-1 align-items-center">
                                                                                    <i class="fa fa-dollar-sign me-1"></i>
                                                                                    <span>Current Bid:</span>
                                                                                </div>
                                                                                <div class="price">
                                                                                    {{ $highest_bid }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div> --}}
                                                                    <div class="col-md-6">
                                                                        <div class="info">
                                                                            <div
                                                                                class="d-flex gap-2 justify-content-start">
                                                                                <div
                                                                                    class="fw-600 d-flex gap-1 align-items-center">
                                                                                    <i class="fa fa-dollar-sign me-1"></i>
                                                                                    <span>Autobid Range:</span>
                                                                                </div>
                                                                                <div class="price">
                                                                                    {{ $autobidRange }}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="d-flex gap-2 py-2">
                                                                    <div class="bid-aamount-text">
                                                                        BID
                                                                        {{ single_price($detailedProduct->starting_bid) }}
                                                                    </div>
                                                                    <div>
                                                                        or place a maximum bid
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                            <form class="bid-form"
                                                                data-product-id="{{ $detailedProduct->id }}"
                                                                onsubmit="event.preventDefault()"
                                                                data-min-bid-amount="{{ $min_bid_amount }}"
                                                                action="{{ route('auction_product_bids.store') }}"
                                                                method="POST" enctype="multipart/form-data">
                                                                @csrf

                                                                <div class="">
                                                                    <label for="switcher-btn" class="switcher-btn">
                                                                        <input type="checkbox" id="switcher-btn"
                                                                            name="autobid" value="1">
                                                                        <span class="slider round"></span>
                                                                        <span class="select-right">Autobid </span>
                                                                        <span class="select-left">Standard</span>
                                                                    </label>
                                                                    <a href="#" data-bs-toggle="modal"
                                                                        data-bs-target="#autobid_info"
                                                                        data-placement="top" data-toggle="tooltip"
                                                                        data-title="Info">
                                                                        <i class="fa fa-info-circle m-2"
                                                                            aria-hidden="true"></i>
                                                                    </a>
                                                                </div>


                                                                <div class="d-flex gap-2 pb-2">
                                                                    <input type="hidden" name="product_id"
                                                                        value="{{ $detailedProduct->id }}">
                                                                    <div style="flex-grow: 1">
                                                                        <input type="number"
                                                                            class="form-control form-control-sm"
                                                                            id="amountInput{{ $detailedProduct->id }}"
                                                                            name="amount" min="{{ $min_bid_amount  + get_next_bid_amount($min_bid_amount)  }}"
                                                                            placeholder="$ 000"
                                                                            value="{{ $min_bid_amount + get_next_bid_amount($min_bid_amount) }}">
                                                                    </div>
                                                                    <div id="bidbutton{{ $detailedProduct->id }}">
                                                                        <button type="button"
                                                                            class="bitBtnAdd btn-sm w-100"
                                                                            onclick="checkMinBidAmount({{ $detailedProduct->id }},{{ $min_bid_amount }})">
                                                                            <span
                                                                                id="placeBidText{{ $detailedProduct->id }}">Place
                                                                                Maximum Bid</span>
                                                                            <span
                                                                                id="processingText{{ $detailedProduct->id }}"
                                                                                style="display: none;">
                                                                                <span class="spinner-border"
                                                                                    role="status"
                                                                                    aria-hidden="true"></span>Processing...
                                                                            </span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <button class="theme-btn-one w-100" onclick="login()">Login to
                                                            bid</button>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                @endif

                            @else
                                <div class="p-3 bg-dark text-center rounded-3 mt-4">
                                    @if ($detailedProduct->sold_status != null)
                                        <div class="fs-18 fw-600"> This product is {{ $detailedProduct->sold_status }}.
                                        </div>
                                    @elseif ($detailedProduct->isAuctionOver())
                                        <div class="fs-18 fw-600"> Auction is over. </div>
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                @if ($detailedProduct->previousProductLot() && $detailedProduct->auction_end_date > strtotime('now'))
                                <a class=""
                                href="{{ route('auction-product', $detailedProduct->previousProductLot()?->slug) }}">
                                <i class="fa-solid fa-arrow-left"></i>
                                Previous Auction
                            </a>
                                @endif
                                @if ($detailedProduct->nextProductLot() && $detailedProduct->auction_end_date > strtotime('now'))
                                <a class="ms-auto"
                                href="{{ route('auction-product', $detailedProduct->nextProductLot()?->slug) }}">Next Auction
                                <i class="fa-solid fa-arrow-right"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="product-content-area mt-5">
                            @php
                                $description = trim($detailedProduct->getTranslation('description'));
                                $terms_conditions = trim(get_page_content('terms'));
                            @endphp
                            @if (!empty($description))
                                <div class="product-content-block">
                                    <h3>Description</h3>
                                    <div class="product-content-content">
                                        @php echo $detailedProduct->getTranslation('description'); @endphp
                                    </div>
                                </div>
                            @endif

                            @if (!empty($terms_conditions))
                                <div class="product-content-block pb-4">
                                    <h3>Terms & Conditions</h3>
                                    <div class="expandable-content p-2">
                                        <div class="product-content-content">
                                            {!! get_page_content('terms') !!}
                                        </div>
                                        <button class="expandable-toggle" onclick="expandText(this)" type="button">
                                            Show
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('frontend.xthome.partials.xt-last-view', [
            'auction' => 'yes',
            'exceptId' => $detailedProduct->id,
        ])
    </div>


    @if ($detailedProduct->auction_product == 1 && $detailedProduct->sold_status == null)
        {{-- Bid Modal --}}
        <div class="modal fade" id="bid_for_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }}
                            <small>({{ translate('Min Bid Amount: ') . $min_bid_amount }})</small>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <div class="form-floating mb-4">
                                    <input type="number" step="0.01" class="form-control" name="amount"
                                        min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}"
                                        required>
                                    <label for="Firstname">{{ translate('Place Bid Price') }}</label>
                                </div>
                                <!-- <div class="form-group">
                                                                                            <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                                                                        </div> -->
                            </div>
                            <div class="form-group">
                                <button type="submit" class="theme-btn-two w-100">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="modal-body gry-bg px-3 pt-3">
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                            <label for="Firstname">{{ translate('Product Name') }}</label>
                        </div>
                        <!-- <div class="form-group">
                                                                                                <input type="text" class="form-control mb-3" name="title" value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}" required>
                                                                                            </div> -->
                        <div class="form-group">
                            <textarea class="form-control" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="theme-btn-two"
                            data-bs-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="theme-btn-one">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @include('frontend.xthome.modal.cancelAutobid', ['productId' => $detailedProduct->id])
    @include('frontend.xthome.modal.autobid_info')
    @include('frontend.xthome.modal.current-bid-list')
    {{-- @include('frontend.xthome.modal.chat_modal') --}}
@endsection
@section('scriptjs')
    <script>
        function updateTimer(startDateTime, endDateTime) {
            const now = moment().tz("{{env('APP_TIMEZONE')}}");

            const startDistance = startDateTime - now;
            const endDistance = endDateTime - now;

            if (startDistance > 0) {
                const days = Math.floor(startDistance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((startDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((startDistance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((startDistance % (1000 * 60)) / 1000);

                updateDisplay(days, 'date');
                updateDisplay(hours, 'hour');
                updateDisplay(minutes, 'minute');
                updateDisplay(seconds, 'second');

                if (startDistance <= 1000) {
                    window.location.reload(true);
                }
            }
            // Auction is ongoing
            else if (startDistance <= 0 && endDistance > 0) {
                const days = Math.floor(endDistance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((endDistance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((endDistance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((endDistance % (1000 * 60)) / 1000);

                updateDisplay(days, 'date');
                updateDisplay(hours, 'hour');
                updateDisplay(minutes, 'minute');
                updateDisplay(seconds, 'second');

                if (endDistance <= 1000) {
                    @if ($detailedProduct->nextProductLot())
                    window.location = '{{ route('auction-product', $detailedProduct->nextProductLot()?->slug) }}'; // Reload when auction ends
                    @else
                    window.location.reload(true);
                    @endif

                }
            }
        }

        function updateDisplay(value, unitId) {
            const unitElement = document.getElementById(unitId);
            if (unitElement) {
                const digits = unitElement.querySelectorAll('.number');
                const formattedValue = formatUnit(value);
                for (let i = 0; i < digits.length; i++) {
                    digits[i].textContent = formattedValue.charAt(i);
                }
            }
        }

        function formatUnit(unit) {
            return String(unit).padStart(2, '0');
        }

        const startDateTime = moment.unix('{{$detailedProduct->auction_start_date}}').tz("{{env('APP_TIMEZONE')}}")
        const endDateTime = moment.unix('{{$detailedProduct->auction_end_date}}').tz("{{env('APP_TIMEZONE')}}")

        updateTimer(startDateTime, endDateTime);
        let intervalId = setInterval(() => {
            updateTimer(startDateTime, endDateTime);
        }, 1000);

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        const login = () => $('#login_modal').modal('show');
        const bid_list_modal = () => $('#bid_list_product').modal('show');
        const autobidinfo = () => $('#autobid_info').modal('show');

        function expandText(btn) {
            const content = $(btn).parents('.expandable-content');
            content.toggleClass('show');
        }

    </script>
@endsection
