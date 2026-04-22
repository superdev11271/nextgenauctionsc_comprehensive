@php
    $total = 0;
    $carts = get_user_cart();
    if (count($carts) > 0) {
        foreach ($carts as $key => $cartItem) {
            $product = get_single_product($cartItem['product_id']);
            $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
        }
    }
@endphp

<div class="offcanvas-header">
    <h5>{{ translate('Cart Items') }} </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
@if (isset($carts) && count($carts) > 0)
    <div class="offcanvas-body">
        <div class="side__cart__body">
            <ul class="side-cart-list">

                @foreach ($carts as $key => $cartItem)
                    @php
                        $product = get_single_product($cartItem['product_id']);
                        $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                        // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                        //$total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                        $product_name_with_choice = Str::limit($product->getTranslation('name'),20);
                        if ($cartItem['variation'] != null) {
                            $product_name_with_choice =
                                Str::limit($product->getTranslation('name'),20) . ' - ' . Str::limit($cartItem['variation'],10);
                        }

                    @endphp
                    @if ($product != null)
                        <li class="side-cart-item">
                            <div class="side-cart-item-box d-flex flex-wrap">
                                @php
                                    $routename = $product->auction_product==1?"auction-product":"product";
                                @endphp
                                @if($product_stock?->image)
                                <a href="{{ route($routename, $product->slug) }}" class="side-cart-item-img">
                                    <img src="{{ uploaded_asset($product_stock->image) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                                @else
                                <a href="{{ route($routename, $product->slug) }}" class="side-cart-item-img">
                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </a>
                                @endif
                                <div class="side-cart-item-text">
                                    <a href="javascript:void(0)" class="side-cart-item-remove"
                                        onclick="removeFromCart({{ $cartItem['id'] }})">
                                        <img src="{{ static_asset('assets/img/close.svg') }}" alt="{{ translate('Remove item') }}">
                                    </a>
                                    {{-- <h6 class="side-cart-item-meta"> {{ $product_name_with_choice }} </h6> --}}
                                    <h4 class="side-cart-item-title">
                                        {{-- <a href="{{ route('product', $product->slug) }}">{{ $product->description }}</a> --}}
                                    </h4>
                                    <h4 class="side-cart-item-title">

                                        <span class="font-weight-600"> {{ $product_name_with_choice }}</span>

                                        <br>
                                        {{-- <span class="font-weight-600">Color: </span><span class="font-14 d-inline-flex gap-2">
                                        @php
                                            $colors = implode(',',json_decode($product->colors));
                                            $colors = explode(',', $colors);

                                            foreach($colors as $color) {
                                                echo '<span style="padding: 8px;height: 4px;width: 4px;display: inline-block;background-color:'.$color.';" class="bg-box"></span>';
                                            }
                                        @endphp
                                    </span> --}}
                                    </h4>
                                    <h3 class="side-cart-item-price">
                                        {{-- <strike class="strike_price">{{ single_price($total) }}</strike> --}}
                                        {{ $cartItem['quantity'] }}x
                                        {{ cart_product_price($cartItem, $product) }}
                                    </h3>
                                    @if ($product->auction_product!=1)
                                    <div class="cart-qty d-inline-flex aiz-plus-minus">
                                        <button type="button" class="qty-btn cart-qty-dec b" data-type="minus"
                                        data-field="quantity[{{ $product->id }}]" disabled="disabled">-</button>
                                        <input type="number" name="quantity[{{ $product->id }}]"
                                        class="cart-qty-input form-control input-number" placeholder="1"
                                        value="{{ $cartItem['quantity']?? '' }}" min="{{ $product->min_qty ?? ''}}"  max="{{ $product_stock->qty ?? '' }}"
                                        onchange="updateQuantity('{{ $cartItem['id'] }}', this)" readonly="">
                                        <button type="button" class="qty-btn cart-qty-inc b" data-type="plus"
                                        data-field="quantity[{{ $product->id }}]">+</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach

            </ul>
            <div class="side-cart-lower"></div>
        </div>
    </div>
    <div class="offcanvas-footer">
        <ul class="side-cart-lower-list">
            <li class="d-flex">
                <span>{{ translate('Subtotal') }}</span>
                <span>{{ single_price($total) }}</span>
            </li>
        </ul>

        @if (Auth::check())
            <div class="side__cart__checkout__btn pt-1 py-3">
                <div class="d-flex gap-2 justify-content-around">
                        <a href="{{ route('cart') }}" class="theme-btn-one btn-sm">
                        {{ translate('View cart') }}
                    </a>
                    <a href="{{ route('checkout.shipping_info') }}" class="theme-btn-two btn-sm">
                        {{ translate('Checkout') }}
                    </a>
                </div>
            </div>
            @else:
                <button href="#" class="theme-btn-one btn-sm btn-block rounded-4" onclick="showLoginModal()">
                        {{ translate('Checkout') }}
            </button>
        @endif
    </div>
@else
    <div class="bg-dark text-center p-3">
        <div class="fs-18 fw-600">
            {{ translate('Your Cart is empty') }}
        </div>
    </div>
@endif

<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>

<script>
    function showLoginModal() {
        $('#login_modal').modal('show');
    }
</script>
