@extends('frontend.layouts.xt-app')

@section('content')
        <!-- shop-section -->
        <section class="shop-section items-containEr  clearfix aos-init aos-animate" data-aos="fade-up">
            <div class="auto-container wow fadeInUp animated animated animated">
                {{-- <div class="d-flex justify-content-end mb-4">
                    <a href="{{ url()->previous() }}" class="theme-btn-one">Go Back</a>
                </div> --}}
                {{-- <div id="carouselExampleControls" class="carousel slide mb-4" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @php
                            $banner = [];
                        @endphp
                        @foreach($products as $key => $product)
                                @if(!in_array($product->banner_image, $banner))
                                    @php
                                        array_push($banner, $product->banner_image);
                                    @endphp
                                    <div class="carousel-item {{ $key == 0 ? 'active' : ''}}">
                                        <img src="{{ uploaded_asset($product->banner_image)}}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/default-auction-banner.png') }}';"  class="d-block w-100" alt="..." style="height: 300px; object-fit: cover;">
                                    </div>
                                @endif
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                      <span class="carousel-control-next-icon" aria-hidden="true"></span>
                      <span class="visually-hidden">Next</span>
                    </button>
                </div> --}}
                <div class="sec-title">
                  <h2>{{ $products->first()?->getCollectionLabel()}}</h2>
                  <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
                </div>

               @if($products->first())
               <div class="row mb-4 bg-dark rounded-2 p-1">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="fs-18 fw-600 m-3">
                       {{__('Auction No')}}: {{ $products->first()->getFormattedAuctionNumber() ?? 'Not Found!' }}
                    </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="fs-18 fw-600 m-3 text-center">
                                    {{__('Total Lots:')}}  {{ $products->total() }}
                            </div>
                    </div>
                </div>
               @endif

               <div class="row">
                @if ($products->count()==0)
                    <div class="bg-dark mt-5 p-3 rounded" role="alert">
                        No Data found
                    </div>
                @endif

                  @foreach($products as $key => $product)
                  <!-- {{ 'frontend/' . get_setting('homepage_select') . '/partials/product_box_xt.blade.php' }} -->
                     @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product])

                  @endforeach
               </div>
               <div class="aiz-pagination aiz-pagination-center mt-4">
                  {{  $products->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
               </div>
            </div>
         </section>
        <!-- instagram-section end -->

        {{-- @include('frontend.xthome.partials.filters') --}}
        @include("auction.frontend.xthome.filterOffCanvas.filter_offcanvas")


        <script>
            function updateTimer(endDateTimeMs) {
                const nowMs = Date.now();
                const distance = endDateTimeMs - nowMs;
                if (distance <= 0) {
                    updateDisplay(0, 'date');
                    updateDisplay(0, 'hour');
                    updateDisplay(0, 'minute');
                    updateDisplay(0, 'second');
                    return false;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                updateDisplay(days, 'date');
                updateDisplay(hours, 'hour');
                updateDisplay(minutes, 'minute');
                updateDisplay(seconds, 'second');
                return true;
            }

            function updateDisplay(value, unitId) {
                const unitElement = document.getElementById(unitId);
                if (!unitElement) {
                    return;
                }
                const digits = unitElement.querySelectorAll('.number');
                const formattedValue = formatUnit(value);
                for (let i = 0; i < digits.length; i++) {
                    digits[i].textContent = formattedValue.charAt(i) || '0';
                }
            }

            function formatUnit(unit) {
                return String(unit).padStart(2, '0');
            }

            const endDateTimeMs = Number('{{ $products->first()?->auction_end_date }}') * 1000;
            if (endDateTimeMs > 0) {
                updateTimer(endDateTimeMs);
                const intervalId = setInterval(() => {
                    const running = updateTimer(endDateTimeMs);
                    if (!running) {
                        clearInterval(intervalId);
                    }
                }, 1000);
            }
        </script>
        <script>
            @include("auction.frontend.xthome.filterOffCanvas.filter_offcanvas_script")
        </script>
@endsection
