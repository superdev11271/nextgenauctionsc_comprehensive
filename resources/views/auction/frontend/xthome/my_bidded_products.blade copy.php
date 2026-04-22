<!-- backup on 26 june 2024 -->
@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/account-details.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css"/>

   <link href="{{ static_asset('xt-assets/libs/slider/css/slick.css') }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/libs/slider/css/jquery.fancybox.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <section class="shop-section account-details pt-5">
        <div class="auto-container">
            <div class="row">
                @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">

                    @if (isset($bids) && count($bids) > 0)
                        <div class="card-header py-3">
                            <h5 class="m-0">{{ translate('All Bidded Products') }}</h5>
                        </div>

                        @if (session()->has('notify_bidder'))
                            <div class="alert text-white fw-700"
                                style="    border-bottom: 0px solid #191919;
                        background-color: #da9a12;"
                                role="alert">
                                {{ session('notify_bidder') }}
                            </div>
                        @endif

                        <div class="card-body light-dark-bg px-4 p-2 table-responsive">
                            <table class="shopping-cart table table-responsive-md  text-nowrap">
                                <thead class="text-gray fs-12">
                                    <tr>
                                        <th class="pl-0">#</th>
                                        <th width="40%">{{ translate('Product') }}</th>
                                        <th width="40%">{{ translate('Auction No.') }}</th>
                                        <th width="40%">{{ translate('Lot No.') }}</th>
                                        <th data-breakpoints="md">{{ translate('My Bid') }}</th>
                                        <th data-breakpoints="md">{{ translate('Highest Bid') }}</th>
                                        <th data-breakpoints="md">{{ translate('End Date') }}</th>
                                        <th class="text-center pr-0">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="fs-14">
                                    @foreach ($bids as $key => $bid_id)
                                        @php
                                            $bid = get_auction_product_bid_info($bid_id->id);
                                        @endphp
                                        <tr class="cart-item">
                                            <td class="pl-0" style="vertical-align: middle;">
                                                {{ sprintf('%02d', $key + 1 + ($bids->currentPage() - 1) * $bids->perPage()) }}
                                            </td>
                                            <td class="text-" style="vertical-align: middle;">
                                                <a href="{{ route('auction-product', $bid->product->slug) }}"
                                                    class="d-flex align-items-center">
                                                    <img class="lazyload m-2"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($bid->product->thumbnail_img) }}"
                                                        alt="{{ $bid->product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <span class=" ml-1">{{ $bid->product->getTranslation('name') }}</span>
                                                </a>
                                            </td>
                                            <td>{{ $bid->product->auction_number }}</td>
                                            <td>{{ $bid->product->lot }}</td>
                                            <td class="fw-700" style="vertical-align: middle;">
                                                {{ single_price($bid->amount) }}</td>
                                            <td style="vertical-align: middle;">
                                                @php $highest_bid = $bid->where('product_id',$bid->product_id)->max('amount'); @endphp
                                                <div
                                                    class="badge @if ($bid->amount < $highest_bid) bg-danger @else bg-success @endif p-2 fs-13">
                                                    {{ single_price($highest_bid) }}
                                                </div>
                                            </td>
                                            <td style="vertical-align: middle;">
                                                @if ($bid->product->auction_end_date < strtotime('now'))
                                                    {{ translate('Ended') }}
                                                @else
                                                    {{ date('d.M H:i', $bid->product->auction_end_date) }}
                                                @endif
                                            </td>


                                            {{-- =================== Options Start ================== --}}
                                            <td class="text-center pr-0" style="vertical-align: middle;">
                                                @php
                                                    $order_detail = get_order_details_by_product($bid->product_id);
                                                    $order =
                                                        $order_detail != null
                                                            ? get_user_order_by_id($order_detail->order_id)
                                                            : null;

                                                    $carts = get_user_cart();

                                                    $cart_has_this_product = false;

                                                    foreach ($carts as $key => $cart) {
                                                        if ($cart->product_id == $bid->product_id) {
                                                            $cart_has_this_product = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp

                                                {{-- if the bidding is ended and customer has the highest bid then show him the buy button --}}
                                                @if ($order != null)
                                                    <span class="badge bg-success p-3 fs-12">{{ translate('Purchased') }}</span>
                                                @elseif ($bid->product->auction_end_date < strtotime('now'))
                                                    {{-- Bidder Won  --}}
                                                    @if (count($carts) > 0)
                                                        @if ($cart_has_this_product)
                                                            <button type="button"
                                                                class="btn btn-sm buy-now fw-600 rounded-0"
                                                                data-toggle="tooltip"
                                                                title="{{ translate('Item alreday added to the cart.') }}">
                                                                {{ translate('Added to cart') }}
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-sm buy-now fw-600 rounded-0"
                                                                data-toggle="tooltip"
                                                                title="{{ translate('Remove other items from cart to add auction product.') }}">
                                                                {{ translate('Buy') }}
                                                            </button>
                                                        @endif
                                                    @else
                                                        @if ($bid->product->sold_to == null)
                                                            <button type="button"
                                                                class="btn btn-sm buy-now fw-600 rounded-0">
                                                                {{ translate('Under Review') }}
                                                            </button>
                                                        @elseif ($bid->product->sold_to != null && $bid->product->sold_to != auth()->user()->id)
                                                            <button type="button"
                                                                class="btn btn-sm btn-warning buy-now fw-600 rounded-0">
                                                                {{ translate('Sold') }}
                                                            </button>
                                                        @else
                                                            <button type="button"
                                                                class="btn btn-sm buy-now fw-600 rounded-0"
                                                                onclick="showAuctionAddToCartModal({{ $bid->product_id }})">
                                                                {{ translate('Buy') }}
                                                            </button>
                                                        @endif
                                                        {{-- <button type="button" class="btn btn-sm buy-now fw-600 rounded-0"
                                                            onclick="showAuctionAddToCartModal({{ $bid->product_id }})">
                                                            {{ translate('Buy') }}
                                                        </button> --}}
                                                    @endif
                                                @else
                                                    N\A
                                                @endif

                                                {{-- Chat Icon --}}
                                                @php
                                                    $unReadChatCount = $bid
                                                        ->chats()
                                                        ->where([
                                                            'viewed' => 0,
                                                            'receiver' => auth()->id(),
                                                            'sender' => $bid->product->user_id,
                                                        ])
                                                        ->count();
                                                @endphp
                                                @if ($bid->chats()->count() && $bid->product->sold_status == null && $bid->status == 'open')
                                                    <a href="{{ route('chat.index', encrypt($bid->id)) }}"
                                                        class="position-relative">
                                                        @if ($unReadChatCount)
                                                            <span class="noti">{{ $unReadChatCount }}</span>
                                                        @endif
                                                        <i class="fa fa-commenting" aria-hidden="true"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            {{-- =================== Options end ================== --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="aiz-pagination">
                                {{ $bids->links() }}
                            </div>
                        </div>
                    @else
                        <div class="auto-container parentsCart  table-responsive">
                            <div class="text-center bg-dark p-4 border">
                                <img class="mw-100 h-100px" src="{{ static_asset('assets/img/nothing.svg') }}"
                                    alt="Image">
                                <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </section>
@endsection


@section('scriptjs')
    <script type="text/javascript">
        function showAuctionAddToCartModal(id) {
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal('show');
            $('.c-preloader').show();
            $.post('{{ route('auction.cart.showCartModal') }}', {
                _token: AIZ.data.csrf,
                id: id
            }, function(data) {
                $('.c-preloader').hide();

                $('#addToCart-modal-body').html(data);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });

        }
    </script>
@endsection
