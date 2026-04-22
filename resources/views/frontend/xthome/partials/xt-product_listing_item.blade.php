

@php
    $cart_added = [];
@endphp


@php
    $product_url = route('product', $product->slug);
    if ($product->auction_product == 1) {
        $product_url = route('auction-product', $product->slug);
    }
@endphp

<div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="shop-block-one i">
        <div class="inner-box position-relative">
            <figure class="image-box">
                <a href="{{ $product_url }}"><img src="{{ get_image($product->thumbnail) }}"
                alt="{{ $product->getTranslation('name') }}" title="{{ $product->getTranslation('name') }}"
                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"> </a>

                <ul class="info-list clearfix">
                    <li><a href="/Product"><i class="fa-regular fa-heart"></i></a></li>
                    @if ($product->auction_product == 0)
                    <li><a href="javascript:void(0)" onclick="showAddToCartModal({{ $product->id }})" >Add to cart</a></li>
                    @endif
                    <li><a class="lightbox-image" data-fancybox="gallery" href="{{ get_image($product->thumbnail) }}"><i class="fa-regular fa-eye"></i></a></li>
                </ul>
            </figure>
            <div class="lower-content">
                <a href="{{ $product_url }}">{{ $product->getTranslation('name') }}</a>
                @if ($product->auction_product == 0)
                    <!-- Previous price -->
                    @if (home_base_price($product) != home_discounted_base_price($product))
                    <span class="price">{{ home_base_price($product) }}</span>
                    @endif
                    <!-- price -->
                    <span class="price">{{ home_discounted_base_price($product) }}</span>
                @endif
                @if ($product->auction_product == 1)
                    <!-- Bid Amount -->
                    <span class="price">{{ single_price($product->starting_bid) }}</span>
                @endif
            </div>

        </div>
    </div>
</div>
