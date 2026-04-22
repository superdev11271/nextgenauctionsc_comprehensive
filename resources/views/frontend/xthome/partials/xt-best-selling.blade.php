@php
    $best_selling_products = get_best_selling_products(8);
@endphp

@if (get_setting('best_selling') == 1 && count($best_selling_products) > 0)
<!-- shop-section -->
        <section class="shop-section">
            <div class="auto-container  wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                <div class="sec-title">
                    <h2>{{ translate('Best Selling') }}</h2>
                    {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, quas.</p> --}}
                    <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
                </div>
                <div class="items-container row clearfix">
                    @foreach ($best_selling_products as $key => $product)
                    @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product])
                    @endforeach
                </div>
                @if(count(get_best_selling_products('')) >='8')
                <div class="more-btn centred"><a href="{{ route('products.bestfeatured', 'best-selling') }}" class="theme-btn-one">View All<i class="fa-solid fa-arrow-right"></i></a></div>
                @endif
            </div>
        </section>

@endif

