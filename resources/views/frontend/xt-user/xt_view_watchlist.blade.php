<!-- ********Last Watch image*********** -->
@if(get_setting('last_viewed_product_activation') == 1 && Auth::check() && auth()->user()->user_type == 'customer')
    <section class="instagram-section">
        <div class="outer-container">
            <div class="sec-title">
                <h2>{{__('watched items')}}</h2>
                @php
                    $lastViewedProducts = getLastViewedProducts();
                @endphp

                <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
            </div>
            @if (count($lastViewedProducts) > 0)
            <div class="six-item-carousel owl-carousel owl-theme owl-dots-none owl-nav-none">
                @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                    @include('frontend.'.get_setting('homepage_select').'.partials.xt-last-view-product-card',['product' => $lastViewedProduct->product])
                @endforeach
            </div>

            <div id="sync2" class="owl-carousel owl-theme">
                @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                    @include('frontend.'.get_setting('homepage_select').'.partials.xt-last-view-product-card',['product' => $lastViewedProduct->product])
                @endforeach
            </div>
            @endif
        </div>
    </section>
@endif
