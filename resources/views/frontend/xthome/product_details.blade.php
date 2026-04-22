@extends('frontend.layouts.xt-app')


@section('meta')
@php
$availability = 'out of stock';
$qty = 0;
if ($detailedProduct->variant_product) {
foreach ($detailedProduct->stocks as $key => $stock) {
$qty += $stock->qty;
}
} else {
$qty = optional($detailedProduct->stocks->first())->qty;
}
if ($qty > 0) {
$availability = 'in stock';
}
@endphp
@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $detailedProduct->meta_title }}">
    <meta itemprop="description" content="{{ $detailedProduct->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $detailedProduct->meta_title }}">
    <meta name="twitter:description" content="{{ $detailedProduct->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}">
    <meta name="twitter:data1" content="{{ single_price($detailedProduct->unit_price) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $detailedProduct->meta_title }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($detailedProduct->meta_img) }}" />
    <meta property="og:description" content="{{ $detailedProduct->meta_description }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ single_price($detailedProduct->unit_price) }}" />
    <meta property="product:brand" content="{{ $detailedProduct->brand ? $detailedProduct->brand->name : env('APP_NAME') }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ number_format($detailedProduct->unit_price, 2) }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency" content="{{ get_system_default_currency()->code }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
@endsection

@push('css')
    <link href="{{ static_asset('xt-assets/libs/slider/css/slick.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/libs/slider/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/libs/slider/css/icon-font.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/product.css') }}" rel="stylesheet">
@endpush

@push('js')
    <script src="{{ static_asset('xt-assets/libs/slider/js/slick.min.js') }}"></script>
    <script src="{{ static_asset('xt-assets/libs/slider/js/jquery.fancybox.min.js') }}"></script>
@endpush

@section('meta_title'){{ $detailedProduct->meta_title }}@stop

@section('meta_description'){{ $detailedProduct->meta_description }}@stop

@section('meta_keywords'){{ $detailedProduct->tags }}@stop

@section('content')

@php
$photos = [];
@endphp
@if ($detailedProduct->photos != null)
@php
$photos = explode(',', $detailedProduct->photos);
@endphp
@endif
<!-- shop-section -->
<div class="shop-section pb-0 pt-5">
    <section id="detail">
        <div class="auto-container wow fadeInUp animated animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ url()->previous() }}" class="theme-btn-one">Go Back</a>
            </div>
            <div class="row">
                <div class="col-lg-6 content-area">
                    <div class="insize position-relative">
                        <div class="wishlist_button">
                            <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }})">
                                <i class="fa-regular
                                watchitem{{ $detailedProduct->id }} {{ isWishlisted($detailedProduct->id) ? 'fa-solid' : '' }}
                                fa-heart"
                                    id="watchitem{{ $detailedProduct->id }}"  data-toggle="tooltip" data-title="{{ isWishlisted($detailedProduct->id) ? translate('Added in watchlist') : translate('Add to watchlist')  }}"></i>

                            </a>
                        </div>

                        <div class=" mt-5 wishlist_button">
                            <a href="javascript:void(0)" onclick="addToCompare({{ $detailedProduct->id }})"
                                data-toggle="tooltip" data-title="{{ isCompare($detailedProduct->id) ? translate('Added in compare') : translate('Add to compare')  }}"
                                data-placement="top"><i id="compare-{{ $detailedProduct->id }}" class="fa-solid fa-code-compare compare-{{ $detailedProduct->id }} {{ isCompare($detailedProduct->id) ? 'text-danger' : '' }}"></i>
                            </a>
                        </div>


                        <div class="magnific-container">

                            <!-- Product Images & Alternates -->
                            <div class="product-images demo-gallery">
                                <!-- Begin Product Images Slider -->
                                <div class="main-img-slider">
                                    @php
                                        $image = optional($detailedProduct->stocks->first())->image;
                                    @endphp
                                    @if($image)
                                        <a data-fancybox="gallery zoom" href="{{ uploaded_asset($image, 'original') }}" id="varient-image-link">
                                            <img src="{{ uploaded_asset($image, 'original') }}" class="img-fluid varient-image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" >
                                        </a>
                                    @else
                                    <a data-fancybox="gallery zoom" href="{{ uploaded_asset($detailedProduct->thumbnail_img, 'original') }}" id="varient-image-link">
                                        <img src="{{ uploaded_asset($detailedProduct->thumbnail_img, 'original') }}" class="img-fluid varient-image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                    @endif
                                    @foreach ($photos as $key => $photo)
                                        <a data-fancybox="gallery zoom" href="{{ uploaded_asset($photo, 'original') }}"><img src="{{ uploaded_asset($photo, 'original') }}" class="img-fluid" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></a>
                                        @endforeach
                                    @foreach ($detailedProduct->attrs as $attribute)
                                        @if (in_array($attribute->type(), [0]))
                                            <a data-fancybox="gallery zoom" href="{{ uploaded_asset($attribute->value) }}"><img src="{{ uploaded_asset($attribute->value) }}" class="img-fluid" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></a>
                                        @endif
                                    @endforeach
                                </div>
                                <!-- End Product Images Slider -->

                                <!-- Begin product thumb nav -->
                                <ul class="thumb-nav">
                                    @if($image)
                                    <li><img src="{{ uploaded_asset($image, 'gallery') }}" class="varient-image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></li>
                                    @else
                                    <li><img src="{{ uploaded_asset($detailedProduct->thumbnail_img, 'gallery') }}" class="varient-image" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></li>
                                    @endif
                                    @foreach ($photos as $key => $photo)
                                        <li><img src="{{ uploaded_asset($photo, 'gallery') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></li>
                                        @endforeach

                                    @foreach ($detailedProduct->attrs as $attribute)
                                        @if (in_array($attribute->type(), [0]))
                                            <li><img src="{{  uploaded_asset($attribute->value)  }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></li>
                                        @endif
                                    @endforeach
                                </ul>
                                <!-- End product thumb nav -->
                            </div>
                            <!-- End Product Images & Alternates -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 product-sidebar">
                    <div class="product-details position-relative">
                        <h1 class="h4"> {{ Str::limit($detailedProduct->getTranslation('name'),100) }}</h1>
                        {{-- <p>@php echo $detailedProduct->getTranslation('description'); @endphp</p> --}}
                        <div class="product-info">
                            @include('frontend.xthome.product_details.details')
                        </div>
                    </div>
                </div>
            </div>

            @include('frontend.xthome.product_details.xt-review')

        </div>
    </section>
    @include('frontend.xthome.partials.xt-last-view',['auction'=>'no',"exceptId"=>$detailedProduct->id])
</div>
@include('frontend.xthome.modal.add-to-cart-view')
@endsection

@section('scriptjs')
    <script id="rendered-js" >
        $('#detail .main-img-slider').slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
          arrows: true,
          fade: true,
          autoplay: true,
          autoplaySpeed: 4000,
          speed: 300,
          lazyLoad: 'ondemand',
          asNavFor: '.thumb-nav',
          prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
          nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>' });

        $('.thumb-nav').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: false,
          centerPadding: '0px',
          asNavFor: '.main-img-slider',
          dots: false,
          centerMode: false,
          draggable: true,
          speed: 200,
          focusOnSelect: true,
          prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
          nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>' });

        $('.main-img-slider').on('afterChange', function (event, slick, currentSlide, nextSlide) {
          $('.thumb-nav .slick-slide').removeClass('slick-current');
          $('.thumb-nav .slick-slide:not(.slick-cloned)').eq(currentSlide).addClass('slick-current');
        });
    </script>
@endsection
