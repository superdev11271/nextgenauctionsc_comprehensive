<div class="modal-body px-4 py-5 c-scrollbar-light">
    <!-- Item added to your cart -->
    <div class="text-center text-success mb-4">
        <p><img src="{{static_asset('xt-assets/images/icons/confirmation.gif')}}" /></p>
        
        <h3 class="fs-28 fw-500">{{ translate('Item added to your cart!')}}</h3>
    </div>

    <!-- Product Info -->
    <div class="media mb-1 text-center">
        @if($product_stock->image)
        <img src="{{ uploaded_asset($product_stock->image) }}" data-src="{{ uploaded_asset($product_stock->image) }}"
            class="mr-4 lazyload size-90px img-fit rounded-0" alt="Product Image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        @else
        <img src="{{ uploaded_asset($product->thumbnail_img) }}" data-src="{{ uploaded_asset($product->thumbnail_img) }}"
            class="mr-4 lazyload size-90px img-fit rounded-0" alt="Product Image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
        @endif
        <div class="media-body mt-2 text-left d-flex flex-column justify-content-between">
            <h5>
                {{  Str::limit($product->getTranslation('name'),20)  }}
            </h5>
            <div class="row m-2 text-center">                
                <div class="col-sm-12">
                    <div class="d-flex gap-2 justify-content-center">
                        <div>{{ translate('Price')}}</div>
                        <div class="fs-16 fw-700">
                            <h5>
                                {{ single_price(cart_product_price($cart, $product, false) * $cart->quantity) }}
                                {{-- {{ single_price(($cart->price + $cart->tax) * $cart->quantity) }} --}}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-2"><a href="{{ route('cart') }}" class="theme-btn-one">{{ translate('Proceed to Checkout')}}</a></div>
                </div>
            </div>
        </div>
       
    </div>


</div>
