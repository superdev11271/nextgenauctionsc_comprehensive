@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/checkout.css" rel="stylesheet">
    <link href="{{ static_asset('xt-assets') }}/css/bootstrap-select.css" rel="stylesheet">

    <style>


    </style>
@endpush
@section('content')
    <!-- banner-section -->
    <div class="shopping-cart">
        <section class="breadcrumb__area separator  breadcrumb__pt-310 include-bg p-relative">
            <div class="auto-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="breadcrumb__content p-relative z-index-1">
                            <h3 class="breadcrumb__title">Checkout</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- checkout-section -->

    <section class="pt-5 mb-4">
        <div class="auto-container">
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col">
                            <div class="text-center tab_top border-bottom-6px p-3">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">1. My Cart</h3>
                            </div>
                        </div>
                        <div class="col ">
                            <div class="text-center tab_top border-bottom-6px p-3">
                                <i class="fa-solid fa-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">2. Shipping info</h3>
                            </div>
                        </div>



                        <div class="col active">
                            <div class="text-center tab_top border-bottom-6 p-3 text-primary">
                                <i class="fa-solid fa-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block ">3. Payment</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center tab_top border-bottom-6px p-3">
                                <i class="fa-solid fa-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">4. Confirmation</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>





    <section class="mb-4">
        <div class="auto-container text-left payment-section">
            <div class="row">
                <div class="col-lg-8">
                    <form action="{{ route('payment.checkout') }}" class="form-default" role="form" method="POST"
                        id="checkout-form">
                        @csrf
                        <div class="card rounded-0 border shadow-none">
                            <!-- Additional Info -->
                            <div class="card-header p-4 border-bottom-0">
                                <h5 class="fw-700  mb-0">
                                    Any additional info?
                                </h5>
                            </div>
                            <div class="form-group px-4">
                                <textarea name="additional_info" rows="5" class="form-control rounded-0" placeholder="Type your text(optional)"></textarea>
                            </div>
                            <div class="card-header p-4 border-bottom-0">
                                <h5 class="fw-700 mb-0">
                                    Select a payment option
                                </h5>
                            </div>
                            <!-- Payment Options -->
                            <div class="card-body text-center px-4 pt-0">
                                <div class="row gutters-10 mb-4">
                                    <!-- Paypal -->
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="paypal" class="online_payment" type="radio"
                                                name="payment_option" checked="checked" />
                                            <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                <img src="{{ static_asset('xt-assets/images/resource/paypal.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span class="d-block fw-600 fs-15">Paypal</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                    <!--Stripe -->
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="stripe" class="online_payment" type="radio"
                                                name="payment_option" />
                                            <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                <img src="{{ static_asset('xt-assets/images/resource/stripe.png') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span class="d-block fw-600 fs-15">Stripe</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>

                                    {{-- <!--Xero -->
                                    <div class="col-6 col-xl-3 col-md-4">
                                        <label class="aiz-megabox d-block mb-3">
                                            <input value="xero" class="online_payment" type="radio"
                                                name="payment_option" />
                                            <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                <img src="{{ static_asset('xt-assets/images/resource/xero-logo.webp') }}"
                                                    class="img-fit mb-2">
                                                <span class="d-block text-center">
                                                    <span class="d-block fw-600 fs-15">Xero</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div> --}}


                                    <!--COD -->
                                    {{-- if cart has auction product: COD option should not be available--}}
                                    @if (!$carts->filter(fn($cart) => $cart->product->auction_product == 1)->count())
                                        <div class="col-6 col-xl-3 col-md-4">
                                            <label class="aiz-megabox d-block mb-3">
                                                <input value="cash_on_delivery" class="online_payment" type="radio"
                                                    name="payment_option" />
                                                <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                                    <img src="{{ static_asset('xt-assets/images/resource/cashondelivary.png') }}"
                                                        class="img-fit mb-2">
                                                    <span class="d-block text-center">
                                                        <span class="d-block fw-600 fs-15">Cash on Delivary</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif

                                    <!-- Mercadopago -->
                                    {{-- <div class="col-6 col-xl-3 col-md-4">
                                <label class="aiz-megabox d-block mb-3">
                                    <input value="mercadopago" class="online_payment" type="radio" name="payment_option" />
                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                        <img src="{{static_asset("xt-assets/images/resource/mercadopago.png")}}" class="img-fit mb-2">
                                        <span class="d-block text-center">
                                            <span class="d-block fw-600 fs-15">Mercadopago</span>
                                        </span>
                                    </span>
                                </label>
                             </div>
                             <!-- sslcommerz -->
                             <div class="col-6 col-xl-3 col-md-4">
                                <label class="aiz-megabox d-block mb-3">
                                    <input value="sslcommerz" class="online_payment" type="radio" name="payment_option" />
                                    <span class="d-block aiz-megabox-elem rounded-0 p-3">
                                        <img src="{{static_asset("xt-assets/images/resource/sslcommerz.png")}}" class="img-fit mb-2">
                                        <span class="d-block text-center">
                                            <span class="d-block fw-600 fs-15">sslcommerz</span>
                                        </span>
                                    </span>
                                </label>
                             </div> --}}
                                </div>

                                <!-- Wallet Payment -->
                                {{-- <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="py-4 px-4 text-center bg-soft-secondary-base mt-4">
                                        <div class="fs-16 ammount-number mb-3">
                                            <span class="opacity-80">Or, Your wallet balance :</span>
                                            <span class="fw-700">$1,703.300</span>
                                        </div>
                                        <button type="button" class="theme-btn-one">Pay with wallet</button>
                                    </div>
                                </div>
                            </div> --}}

                                <!-- Agree Box -->
                                {{-- <div class="row align-items-center pb-5">
                                    <div class="col-md-12 pt-3 px-4 text-gray">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" required="" id="agree_checkbox">
                                            <span class="aiz-square-check"></span>
                                            <span>I agree to the</span>
                                        </label>
                                        <a href="#" class="fw-300 text-decoration-underline">terms and conditions</a>,
                                        <a href="#" class="fw-300 text-decoration-underline">Return Policy</a> &amp;
                                        <a href="#" class="fw-300 text-decoration-underline">Privacy Policy</a>
                                    </div>
                                </div> --}}
                                <div class="row align-items-center pb-5">
                                    <!-- Return to shop -->
                                    <div class="col-md-6 text-start order-1 order-md-0">
                                        <a href="{{ url()->previous() }}" class="theme-btn-two">
                                            Back
                                        </a>
                                    </div>
                                    <!-- Continue to Shipping -->
                                    <div class="col-md-6 text-lg-end pb-4 pb-lg-0">
                                        <button type="submit" id="submitButton" class="theme-btn-one px-4">
                                            Complete Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Cart Summary -->
                <div class="col-lg-4 mt-lg-0 mt-4" id="cart_summary">
                    <div class="card rounded-0 border shadow-none">
                        <!-- Club point -->
                        <div class="card-header px-4 pt-1 w-100 d-flex align-items-center justify-content-between pt-3">
                            <h5 class="mb-0">Summary</h5>
                            <div class="text-right">
                                <span class="badge bg-info text-white">
                                    {{ $carts->count() }}
                                </span>
                            </div>
                        </div>

                        {{-- <div class="px-4 pt-1 w-100 d-flex align-items-center justify-content-between">
                        <p class="product-name">Total Clubpoint</p>
                        <div class="text-right">
                            <span class="badge bg-info text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" class="mr-2">
                                    <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                    <circle id="Ellipse_39" data-name="Ellipse 39" cx="6" cy="6" r="6" transform="translate(973 633)" fill="#fff"></circle>
                                    <g id="Group_23920" data-name="Group 23920" transform="translate(973 633)">
                                        <path id="Path_28698" data-name="Path 28698" d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)" fill="#f3af3d"></path>
                                        <path id="Path_28699" data-name="Path 28699" d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)" fill="#f3af3d" opacity="0.5"></path>
                                        <path id="Path_28700" data-name="Path 28700" d="M12.666,3h1L15,5,12,9l1.664-4Z" transform="translate(-5.995 0)" fill="#f3af3d"></path>
                                    </g>
                                    </g>
                                </svg>
                                1350
                            </span>
                        </div>
                    </div> --}}


                        <div class="card-body">
                            <!-- Products Info -->
                            <table class="table bg-transparent">
                                <thead>
                                    <tr>
                                        <th class="product-name border-top-0 border-bottom-1 pl-0 fs-12 opacity-60">Product
                                        </th>
                                        <th
                                            class="product-total text-right border-top-0 border-bottom-1 pr-0 fs-12 fw-400 opacity-60">
                                            Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($carts as $cart)
                                        <tr class="cart_item">
                                            <td class="product-name pl-0 border-bottom text-grey">
                                                {{ $cart->product->name }}
                                                <strong class="product-quantity">× {{ $cart->quantity }}</strong>
                                            </td>
                                            <td class="product-total pr-0  text-grey border-bottom text-right">
                                                <span class="pl-4 pr-0"> {{ $cart->price }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="hidden" id="sub_total" value="195">
                            <table class="table" style="margin-top: 2rem!important;">
                                <tfoot>
                                    <!-- Subtotal -->
                                    <tr class="cart-subtotal">
                                        <th class="pl-0 pt-0 pb-2 text-grey fw-700 border-bottom">Subtotal</th>
                                        <td class="text-right pr-0 pb-2 text-grey fw-500 border-bottom">
                                            <span class="fw-600">{{ format_price($subtotal) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Tax -->
                                    <tr class="cart-shipping">
                                        <th class="pl-0 pt-0 pb-2  text-grey fw-700 border-bottom">Tax</th>
                                        <td class="text-right pr-0 pt-0 pb-2  text-grey fw-500 border-bottom">
                                            <span class="fw-600">{{ format_price($tax) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Total Shipping -->
                                    <tr class="cart-shipping">
                                        <th class="pl-0 pt-0 pb-2  text-grey fw-700 border-bottom">Total Shipping</th>
                                        <td class="text-right pr-0 pt-0 pb-2  text-grey fw-500 border-bottom">
                                            <span class="fw-600">{{ format_price($shipping) }}</span>
                                        </td>
                                    </tr>
                                    <!-- Redeem point -->

                                    <!-- Coupon Discount -->
                                    <!-- Total -->
                                    <tr class="cart-total">
                                        <th class="pl-0  text-grey fw-700 border-bottom"><span
                                                class="strong-600">Total</span></th>
                                        <td class="text-right pr-0  text-grey fw-700 border-bottom">
                                            <strong><span>{{ format_price($total) }}</span></strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- Coupon System -->
                            <div class="mt-3">
                                <form id="apply-coupon-form">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control rounded-0 py-1" name="code"
                                            placeholder="Have coupon code? Apply here" required="">
                                        <div class="input-group-append">
                                            <button type="button" id="coupon-apply"
                                                class="theme-btn-card">Apply</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('modal')
    @if (Auth::check())
        @include('frontend.' . get_setting('homepage_select') . '.modal.xt_address_modal')
    @endif
@endsection

@push('js')
    <script>
        $(document).on("click", "#coupon-apply", function() {
            var data = new FormData($('#apply-coupon-form')[0]);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.apply_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    AIZ.plugins.notify(data.response_message.response, data.response_message.message);
                    $("#cart_summary").html(data.html);
                }
            })
        });

        $(document).on("click", "#coupon-remove", function() {
            var data = new FormData($('#remove-coupon-form')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: "POST",
                url: "{{ route('checkout.remove_coupon_code') }}",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data, textStatus, jqXHR) {
                    $("#cart_summary").html(data);
                }
            })
        })
    </script>
    <script>
        function disableButton(button) {
            button.submit()
            button.disabled = true;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("checkout-form");
            var submitButton = document.getElementById("submitButton");

            form.addEventListener("submit", function(event) {
                submitButton.disabled = true;
            });
        });
    </script>
@endpush()
