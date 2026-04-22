<!-- shop-section -->
@if (count($newest_products) > 0)
    <section class="shop-section">
        <div class="auto-container  wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
            <div class="sec-title">
                <h2>{{ translate('Market Place') }}</h2>
                {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, quas.</p> --}}
                <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
            </div>
            <div class="items-container row clearfix">
                @foreach ($newest_products as $key => $new_product)
                    @include('frontend.' . get_setting('homepage_select') . '.partials.product_box_xt', [
                        'product' => $new_product,
                    ])
                @endforeach
            </div>
            <div class="more-btn centred"><a href="{{ route('marketplace')}}" class="theme-btn-one">View All<i
                        class="fa-solid fa-arrow-right"></i></a></div>
        </div>
    </section>
@endif
