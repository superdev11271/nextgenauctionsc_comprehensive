<!-- ********Last Watch image*********** -->
@if (get_setting('last_viewed_product_activation') == 1)
    @if ($auction == 'yes')
        @php
            $lastViewedProducts = getLastViewedAuctionProducts($exceptId);
            $currentTime = now()->timestamp;
            $lastViewedProducts = $lastViewedProducts
                ->where('auction_start_date', '<=', $currentTime)
                ->where('auction_end_date', '>=', $currentTime);
        @endphp

        @if ($lastViewedProducts && !$lastViewedProducts->isEmpty())
            <section class="shop-section">
                <div class="outer-container">
                    <div class="sec-title">
                        <h2>{{ __('Recent View') }}</h2>
                        <span class="separator"
                            style="background-image: url({{ static_asset('xt-assets') }}/images/icons/separator-1.png);"></span>
                    </div>
                    @if (count($lastViewedProducts) > 0)
                        <div class="six-item-carousel owl-carousel owl-theme owl-dots-none owl-nav-none">
                            @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                                @include(
                                    'auction.frontend.' .
                                        get_setting('homepage_select') .
                                        '.partials.product_box_xt',
                                    ['product' => $lastViewedProduct]
                                )
                            @endforeach
                        </div>
                        {{-- <div id="sync2" class="owl-carousel owl-theme">
                            @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                                @include(
                                    'auction.frontend.' .
                                        get_setting('homepage_select') .
                                        '.partials.product_box_xt',
                                    ['product' => $lastViewedProduct]
                                )
                            @endforeach
                        </div> --}}
                    @endif
                </div>
            </section>
        @endif
    @else
        @php
            $lastViewedProducts = getLastViewedProductsWithoutAuth($exceptId ?? null); //getLastViewedProducts()
        @endphp
        @if ($lastViewedProducts && !$lastViewedProducts->isEmpty())
            <section class="shop-section">
                <div class="outer-container">
                    <div class="sec-title">
                        <h2>{{ __('Recent View') }}</h2>


                        <span class="separator"
                            style="background-image: url({{ static_asset('xt-assets') }}/images/icons/separator-1.png);"></span>
                    </div>
                    @if (count($lastViewedProducts) > 0)
                        <div class="six-item-carousel owl-carousel owl-theme owl-dots-none owl-nav-none">
                            @foreach ($lastViewedProducts as $key => $lastViewedProduct)
                                @include(
                                    'frontend.' .
                                        get_setting('homepage_select') .
                                        '.partials.xt-last-view-product-card',
                                    ['product' => $lastViewedProduct]
                                )
                            @endforeach

                        </div>
                    @endif
                </div>
            </section>
        @endif
    @endif
@endif
