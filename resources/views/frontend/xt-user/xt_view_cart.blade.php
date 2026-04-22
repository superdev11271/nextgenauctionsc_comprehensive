@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/checkout.css" rel="stylesheet">
@endpush
@section('content')
    <!-- banner-section -->
    <div class="shopping-cart">
        <section class="breadcrumb__area separator  breadcrumb__pt-310 include-bg p-relative">
           <div class="auto-container">
              <div class="row">
                 <div class="col-md-12">
                    <div class="breadcrumb__content p-relative z-index-1">
                       <h3 class="breadcrumb__title">Cart</h3>
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

                    <div class="col active">
                        <div class="text-center tab_top border-bottom-6 p-3 text-primary">
                           <i class="fa-solid fa-cart-shopping"></i>
                           <h3 class="fs-14 fw-600 d-none d-lg-block">1. My Cart </h3>
                        </div>
                     </div>
                     <div class="col">
                        <div class="text-center tab_top border-bottom-6px p-3">
                             <i class="fa-solid fa-map"></i>
                             <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">2. Shipping info</h3>
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
     <section class="mb-4 cart-summary" id="cart-summary">
        @include('frontend.'.get_setting('homepage_select').'.partials.xt-cart-listing', ['carts' => $carts])
     </section>

@endsection
@section('scriptjs')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();
            removeFromCart(key,'cart');
        }
    </script>
@endsection
