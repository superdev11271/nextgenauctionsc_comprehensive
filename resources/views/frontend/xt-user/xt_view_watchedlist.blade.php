@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/account-details.css" rel="stylesheet">
@endpush
@section('content')
    <!-- banner-section -->
    <section class="shop-section account-details pt-5">
        <div class="auto-container">
            <div class="row">
                @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">


                    <ul class="nav nav-tabs">
                        <li><a href="{{ route('wishlists.auction') }}" class="{{ activeRoute(['wishlists.auction']) }}">Auction <span id="auction_count">@if($auction_products_count > 0) {{ $auction_products_count }} @endif</span></a></li>
                        <li class="shop-cartspan"><a href="{{ route('wishlists.index') }}" class="{{ activeRoute(['wishlists.index']) }}">Marketplace <span id="non_auction_count">@if($non_auction_products_count > 0) {{ $non_auction_products_count }} @endif</span></a></li>
                    </ul>



                    @if (count($watchlists) > 0)
                        <div class="items-container row clearfix mt-4">
                            @foreach ($watchlists as $key => $watchlist)
                                @if($watchlist->product)
                                @include(
                                    'frontend.' .
                                        get_setting('homepage_select') .
                                        '.partials.xt_watch_product_box',
                                    ['product' => $watchlist->product, 'wishlist_id' => $watchlist->id]
                                )
                                @endif
                            @endforeach
                        </div>
                    @else
                    <div class="rounded bg-dark text-center p-3 mt-1">
                            <img class="mw-100 h-100px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image" />
                            <div class="fs-18 fw-600">
                                {{ translate("There isn't anything added yet") }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
    </section>
    <script type="text/javascript">
        function removeFromWishlist(id, isAuction) {
            $.post('{{ route('wishlists.remove') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#wishlist').html(data);
                $('#wishlist_' + id).hide();
                AIZ.plugins.notify('success', '{{ translate('Item has been removed from wishlist') }}');
    
                if (isAuction) {
                    var auctionCountElement = $('#auction_count');
                    var auctionCount = parseInt(auctionCountElement.text());
                    if (auctionCount) {
                        auctionCountElement.text(auctionCount - 1);
                    }
                } else {
                    var nonAuctionCountElement = $('#non_auction_count');
                    var nonAuctionCount = parseInt(nonAuctionCountElement.text());
                    if (nonAuctionCount) {
                        nonAuctionCountElement.text(nonAuctionCount - 1);
                    }
                }
            });
        }
    </script>
@endsection
