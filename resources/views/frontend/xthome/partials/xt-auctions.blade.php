@php
    $get_auction_products = get_auction_products(null, 4, true,auctionType:'live');

@endphp

 <!-- shop-section -->
 @if (count($get_auction_products) > 0)
    <section class="shop-section">
        <div class="auto-container wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
            <div class="sec-title">
                <h2>Live Auction</h2>
                <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
            </div>
            <div class="items-container row clearfix">

                {{-- @foreach ($get_auction_products as $key => $product)

                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product])

                @endforeach --}}
                @foreach ($get_auction_products as $key => $product)
                    @include(
                    'frontend.' . get_setting('homepage_select') . '.partials.product_box_xt_collection',
                    ['product' => $product->lots])
                @endforeach
            </div>
            <div class="more-btn centred"><a href="{{route("auction_collection")}}" class="theme-btn-one">View All<i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
    </section>
        <!-- shop-section end -->
@endif`
