@extends('frontend.layouts.xt-app')

@if (isset($category_id))
    @php
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title         = get_setting('meta_title');
        $meta_description   = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection


@section('content')


<!-- shop-section -->
<section class="shop-section items-container  clearfix aos-init aos-animate" data-aos="fade-up">
    <div class="auto-container wow fadeInUp animated animated animated">
        <div class="sec-title">
            <h2>{{ translate('Home')}}</h2>
            <ul class="breadcrumb bg-transparent py-0 px-1">
                <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                    <a class="text-reset" href="{{ route('home') }}">{{ translate('Home')}}</a>
                </li>
                @if(!isset($category_id))
                    <li class="breadcrumb-item fw-700  text-dark">
                        "{{ translate('All Categories')}}"
                    </li>
                @else
                    <li class="breadcrumb-item opacity-50 hov-opacity-100">
                        <a class="text-reset" href="{{ route('search') }}">{{ translate('All Categories')}}</a>
                    </li>
                @endif
                @if(isset($category_id))
                    <li class="text-dark fw-600 breadcrumb-item">
                        "{{ $category->getTranslation('name') }}"
                    </li>
                @endif
            </ul>
            <span class="separator" style="background-image: url('{{ static_asset('xt-assets/images/icons/separator-1.png') }}');"></span>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <div class="shop__all__top__bar">
                    <div class="shop__all__top__result__count" id="recoard_details">Showing 1 - 10 of 10 results</div>
                    <div class="shop__all__top__filter__btn">
                        <a class="btn btn-default" data-bs-toggle="offcanvas" href="#shopFilterbar" role="button">
                            <img src="{{ static_asset('xt-assets/images/icons/filter-icon.svg') }}" alt="">
                            Filter
                        </a>
                    </div>
                    <div class="shop__all__top__left">
                        <div class="hero__search__bar">
                            <input type="search" name="keyword" value="" placeholder="Search..." class="hero__search__input">
                            <button type="submit" class="hero__search__btn">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18.031 16.617L22.314 20.899L20.899 22.314L16.617 18.031C15.0237 19.3082 13.042 20.0029 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20.0029 13.042 19.3082 15.0237 18.031 16.617ZM16.025 15.875C17.2941 14.5699 18.0029 12.8204 18 11C18 7.133 14.867 4 11 4C7.133 4 4 7.133 4 11C4 14.867 7.133 18 11 18C12.8204 18.0029 14.5699 17.2941 15.875 16.025L16.025 15.875Z" fill="black"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="shop__all__top__right">
                        <select class="form-control form-select" name="sort_by" onchange="filter()">
                            <option value="">Sort by</option>
                            <option value="default">Default sorting</option>
                            <option value="popular">Sort by popularity</option>
                            <option value="newest">Sort by newness</option>
                            <option value="price-asc">Sort by price: low to high</option>
                            <option value="price-desc">Sort by price: high to low</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($products as $key => $product)
                 @include('frontend.'.get_setting('homepage_select').'.partials.xt-product_listing_item',['product' => $product])
            @endforeach
        </div>

        <div class="aiz-pagination aiz-pagination-center mt-4">
                {{-- $products->appends(request()->input())->links('pagination::bootstrap-5') --}}
                {{  $products->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
        </div>
    </div>
</section>
<!-- instagram-section end -->

@include('frontend.xthome.partials.filters')


@endsection
