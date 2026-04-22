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



                        <div class="col">
                            <div class="text-center tab_top border-bottom-6 p-3">
                                <i class="fa-solid fa-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-5 ">3. Payment</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center tab_top border-bottom-6 p-3 text-primary">
                                <i class="fa-solid fa-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">4. Confirmation</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="mb-4 cart-summary" id="cart-summary">
        <div class="auto-container">
            <div class="row cart-footer pt-4">
                <div class="col-sm-12 cart-total">
                    <div class="bg-dark clearfix p-5">
                        <div class="row justify-content-center order-confirm">
                            <div class="col-md-8 col-lg-6 text-center">
                                <p class="text-center pb-4"><img
                                        src="{{ static_asset('xt-assets/images/confirmation.gif') }}" class="confirmation">
                                </p>
                                <h3>Thank You For Your Order</h3>
                                <p class="pt-2">You will receive an email of your order details</p>
                                <p class="pb-4">
                                    {{-- You've just ordered
                                    Nike Sportswear. Your Order: #95475261
                                    <br> --}}
                                    Your order confirmation and receipt is sent to: {{ $email }}
                                    {{-- <br>
                                    Your order will be shipped to: Ring road 254, Lucknow --}}
                                </p>
                                <!-- Continue to Shipping -->
                                   
                                    <a href="{{ route('purchase_history.details', $order) }}" class="theme-btn-one px-4">
                                        Order Details
                                    </a>
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
