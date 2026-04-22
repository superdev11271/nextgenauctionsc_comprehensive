@php
    $get_upcoming_auction_products = get_upcoming_auction_products(8) //get_upcoming_auction_products(8);
    
@endphp

 <!-- shop-section -->
 @if (count($get_upcoming_auction_products) > 0)
    <section class="shop-section">
        <div class="auto-container wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
            <div class="sec-title">
                <h2>Upcoming Auction</h2>

                <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
            </div>
            <div class="items-container row clearfix">

                {{-- @foreach ($get_auction_products as $key => $product)

                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product])

                @endforeach --}}
                @foreach ($get_upcoming_auction_products as $key => $product)
                    {{-- @if (count($product) === 1) --}}
                        {{-- @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product->first()]) --}}
                    {{-- @else --}}
                        @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt_collection',['product' => $product])
                    {{-- @endif  --}}
                @endforeach
            </div>
            <div class="more-btn centred"><a href="{{route("upcoming_auction_collection")}}" class="theme-btn-one">View All<i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
    </section>
        <!-- shop-section end -->
@endif`
