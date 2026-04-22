<!----------------------My Code-------------------------------->
<div class="auto-container">
    <div class="row cart-footer pt-4">
        <div class="col-sm-12 cart-total">
            @if ($carts && count($carts) > 0)
                <div class="table-responsive">
                    <table class="shopping-cart table">
                        <thead>
                            <tr>
                                <th class="image-product">{{ translate('Product') }}</th>
                                <th>{{ translate('Price') }}</th>
                                <th>{{ translate('Quantity') }}</th>
                                <th>{{ translate('Tax') }}</th>
                                <th>{{ translate('Total') }}</th>
                                <th class="w-30 text-right">{{ translate('Remove') }}</th>
                            </tr>
                        </thead>

                            <tbody>
                                @php
                                $total = 0;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                            @php
                                    $product = get_single_product($cartItem['product_id']);
                                    $product_stock = $product->stocks->where('variant', $cartItem['variation'])->first();
                                    // $total = $total + ($cartItem['price']  + $cartItem['tax']) * $cartItem['quantity'];
                                    $total = $total + cart_product_price($cartItem, $product, false) * $cartItem['quantity'];
                                    $product_name_with_choice = Str::limit($product->getTranslation('name'),30);
                                    if ($cartItem['variation'] != null) {
                                        $product_name_with_choice =  Str::limit($product->getTranslation('name'),20).' - '.$cartItem['variation'];
                                    }
                                @endphp
                                <tr class="cart-item" id="cart_{{ $cartItem['id'] }}">
                                    <td>
                                        <div class="d-flex flex-wrap gap-3 align-items-center">
                                            <span>
                                                @if($product_stock?->image)
                                                <a href="#"><img src="{{ uploaded_asset($product_stock->image) }}" class="img-fit size-70px"     alt="{{ $product->getTranslation('name') }}"  onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}'"></a>
                                                @else
                                                    <a href="#"><img src="{{ uploaded_asset($product->thumbnail_img) }}" class="img-fit size-70px"     alt="{{ $product->getTranslation('name') }}"  onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}'"></a>
                                                @endif
                                            </span>
                                            <span class="d-none d-lg-block">{{ $product_name_with_choice }}</span>
                                        </div>
                                    </td>
                                    <td>{{ cart_product_price($cartItem, $product, true, false) }}</td>
                                    <td class="qty">
                                        @if ($cartItem['digital'] != 1 && $product->auction_product == 0)
                                        <div class="cart-qty d-flex justify-content-between flex aiz-plus-minus">
                                            <button type="button" class="qty-btn  cart-qty-dec a" data-type="minus"
                                                data-field="quantity[{{ $cartItem['id'] }}]" disabled="disabled">-</button>
                                            <input type="number" name="quantity[{{ $cartItem['id'] }}]"
                                                class="cart-qty-input form-control input-number" placeholder="1"
                                                value="{{ $cartItem['quantity'] }}" min="{{ $product->min_qty }}"  max="{{ $product_stock?->qty }}"
                                                onchange="updateQuantity1({{ $cartItem['id'] }}, this)" readonly="">
                                            <button type="button" class="qty-btn cart-qty-inc a" data-type="plus"
                                                data-field="quantity[{{ $cartItem['id'] }}]">+</button>
                                        </div>
                                        @elseif($product->auction_product == 1)
                                                <span class="fw-700 fs-14">1</span>
                                        @endif
                                    </td>
                                    <td>{{ cart_product_tax($cartItem, $product) }}</td>
                                    <td class="fw-700 fs-16 ammount-number" data-id="{{ $cartItem['id'] }}">{{ single_price(cart_product_price($cartItem, $product, false) * $cartItem['quantity']) }}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn-close" onclick="removeFromCartView(event, {{ $cartItem['id'] }})" data-bs-dismiss="atz"
                                            aria-label="Close"></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
                <div class="row cart-footer pt-4">
                    <div class="col-sm-12 cart-total">
                        <!-- Subtotal -->
                        <div class="px-0 py-2 mb-4 border-top d-flex justify-content-end gap-3">
                            <span class="opacity-60 fs-14">{{translate('Subtotal')}}</span>
                            <span class="fw-700 fs-16 cart-products-subtotal">{{ single_price($total) }}</span>
                        </div>
                        <div class="row align-items-center">
                            <!-- Return to shop -->
                            <div class="col-md-6 text-start order-1 order-md-0">
                                <!-- <a href="{{ url()->previous() }}" class="theme-btn-two">
                                    {{ translate('Back')}}
                                </a> -->
                            </div>
                            <!-- Continue to Shipping -->
                            @if(Auth::check())
                            <div class="col-md-6 text-lg-end pb-4 pb-lg-0">
                                <a href="{{ route('checkout.shipping_info') }}" class="theme-btn-one px-4">
                                    {{ translate('Continue to Shipping')}}
                                </a>
                            </div>
                            @else
                            <div class="col-md-6 text-lg-end pb-4 pb-lg-0"> <a href="#" class="theme-btn-one px-4" onclick="showLoginModal()">{{ translate('Continue to Shipping')}} </a></div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xl-12 col-lg-12 col-md-12 py-3">
                    <div class="rounded bg-dark text-center p-3">
                            <div class="fs-18 fw-600">
                                {{ translate('Your Cart is empty') }}
                            </div>

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@section('scriptjs')
<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key,'cart');
        }
    </script>
@endsection
