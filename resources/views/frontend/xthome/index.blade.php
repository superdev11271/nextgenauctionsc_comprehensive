@extends('frontend.layouts.xt-app')

@section('content')
    <h1 class="visually-hidden">{{ translate('Next Gen Auctions Home') }}</h1>
    @include('frontend.xthome.partials.xt-home-banner')
    @include('frontend.xthome.partials.xt-top-category')
    @include('frontend.xthome.partials.xt-auctions')
    @include('frontend.xthome.partials.xt-upcomming-auctions')
    @include('frontend.xthome.partials.xt-new-arrivals')
    @include('frontend.xthome.partials.xt-best-selling')
    @include('frontend.xthome.partials.xt-featured-products-section')
    @include('frontend.xthome.partials.xt-today-deals-product')
    @include('frontend.xthome.partials.xt-whychoseus')
    @include('frontend.xthome.partials.xt-news')
    {{-- @include('frontend.xthome.modal.xt-landing') --}}
@endsection
