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
                        <div class="col active">
                            <div class="text-center tab_top border-bottom-6 p-3 text-primary">
                                <i class="fa-solid fa-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">2. Shipping info</h3>
                            </div>
                        </div>



                        <div class="col">
                            <div class="text-center tab_top border-bottom-6px p-3">
                                <i class="fa-solid fa-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">3. Payment</h3>
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
        <div class="auto-container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-xxl-12 col-xl-12 mx-auto">
                    <form class="form-default" id="shipping_info_form" data-toggle="validator"
                        action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST">
                        @csrf
                        <div class="border p-3 p-lg-4 mb-4">
                            <div class="mb-4 p-3">
                                <div class="row">

                                    <div class="col-md-6">
                                        <h5 class="pb-2 add_head">{{ translate('Shipping Address') }}</h5>
                                                <div class="border-dark p-2 p-lg-3">
                                                    @if (Auth::check())
                                                        <input type="hidden" name="checkout_type" value="logged">
                                                        @foreach (Auth::user()->addresses as $key => $address)
                                                            <!-- ***********address start********* -->
                                                            @if ($address->address_type == '1')
                                                                <div class="row pb-3 mb-3 border-bottom">
                                                                    <div class="col-md-8">
                                                                        <div
                                                                            class="d-flex pY-3 aiz-megabox-elem border-0 gap-3">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="radio"
                                                                                    name="address_id"
                                                                                    value="{{ $address->id }}"
                                                                                    @if ($address->set_default == '1') checked @endif
                                                                                    required>
                                                                                <label class="form-check-label"
                                                                                    for="{{ $address->id }}"></label>

                                                                            </div>

                                                                            <!-- Address -->
                                                                            <div class="flex-grow-1 pl-3 text-left">
                                                                                <div class="row">
                                                                                    <div
                                                                                        class="fs-14 col-12 col-lg-4 fw-700">
                                                                                        {{ translate('Address') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col-12">
                                                                                        {{ $address->address }}</div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Postal Code') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ $address->postal_code }}</div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('City') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->city)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('State') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->state)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Country') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->country)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Phone') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ $address->phone }}</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Edit Address Button -->
                                                                    <div class="col-md-4 p-3 text-right mt-2">
                                                                        <a href="javascript:void(0)"
                                                                            class="theme-btn-one px-4"
                                                                            onclick="edit_address('{{ $address->id }}')">Change</a>
                                                                    </div>
                                                                </div>
                                                                <!-- ***********address end********* -->
                                                            @endif
                                                        @endforeach

                                                        <div class="row">
                                                            <div class="mt-4">
                                                                <div class="border-dark p-3 c-pointer text-center bg-dark has-transition hov-bg-soft-light h-100 flex-column justify-content-center"
                                                                    data-addresstype="billing"
                                                                    data-bs-target="#new-address-modal"
                                                                    data-bs-toggle="modal">
                                                                    <i class="fa-solid fa-plus la-2x mb-0"></i>
                                                                    <div class="alpha-7 fw-700">
                                                                        {{ translate('Add new address') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="pb-2 add_head">{{ translate('Billing Address') }}</h5>
                                                <div class="border-dark p-2 p-lg-3">
                                                    @if (Auth::check())
                                                        <input type="hidden" name="checkout_type" value="logged">
                                                        @foreach (Auth::user()->addresses as $key => $address)
                                                            @if ($address->address_type == '2')
                                                                <!-- ***********address start********* -->
                                                                <div class="row pb-3 mb-3 border-bottom">
                                                                    <div class="col-md-8">
                                                                        <div
                                                                            class="d-flex pY-3 aiz-megabox-elem border-0 gap-3">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="radio"
                                                                                    name="billing_address_id"
                                                                                    value="{{ $address->id }}"
                                                                                    @if ($address->set_default == '1') checked @endif
                                                                                    required>
                                                                                <label class="form-check-label"
                                                                                    for="{{ $address->id }}"></label>
                                                                            </div>

                                                                            <!-- Address -->
                                                                            <div class="flex-grow-1 pl-3 text-left">
                                                                                <div class="row">
                                                                                    <div
                                                                                        class="fs-14 col-12 col-lg-4 fw-700">
                                                                                        {{ translate('Address') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col-12">
                                                                                        {{ $address->address }}</div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Postal Code') }}
                                                                                    </div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ $address->postal_code }}</div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('City') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->city)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('State') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->state)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Country') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ optional($address->country)->name }}
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="fs-14 col-4 col-lg-4">
                                                                                        {{ translate('Phone') }}</div>
                                                                                    <div class="fs-14 fw-500 ml-2 col">
                                                                                        {{ $address->phone }}</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Edit Address Button -->
                                                                    <div class="col-md-4 p-3 text-right mt-2">
                                                                        <a href="javascript:void(0)"
                                                                            class="theme-btn-one px-4"
                                                                            onclick="edit_address('{{ $address->id }}')">Change</a>
                                                                    </div>
                                                                </div>
                                                                <!-- ***********address end********* -->
                                                            @endif
                                                        @endforeach

                                                        <div class="row">
                                                            <div class="mt-4">
                                                                <div class="border-dark p-3 c-pointer text-center bg-dark has-transition hov-bg-soft-light h-100 flex-column justify-content-center"
                                                                    data-addresstype="shipping"
                                                                    data-bs-target="#new-address-modal"
                                                                    data-bs-toggle="modal">
                                                                    <i class="fa-solid fa-plus la-2x mb-0"></i>
                                                                    <div class="alpha-7 fw-700">
                                                                        {{ translate('Add new address') }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Add New Address -->

                            <div class="row cart-footer pt-4">
                                <div class="col-sm-12 cart-total">
                                    <div class="row align-items-center">
                                        <!-- Return to shop -->
                                        <div class="col-md-6 text-start order-1 order-md-0">
                                            <a href="{{ route('cart') }}" class="theme-btn-two">
                                                {{ translate('Back') }}
                                            </a>
                                        </div>
                                        <!-- Continue to Shipping -->
                                        <div class="col-md-6 text-lg-end pb-4 pb-lg-0">
                                            <button type="submit" class="theme-btn-one px-4">
                                                {{ translate('Continue to Pay') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
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
