@extends('frontend.layouts.xt-app')

@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $page->meta_title }}">
    <meta itemprop="description" content="{{ $page->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $page->meta_title }}">
    <meta name="twitter:description" content="{{ $page->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $page->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ URL($page->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
    <meta property="og:description" content="{{ $page->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')


<!-- banner-section -->
<div class="shopping-cart">
    <section class="breadcrumb__area separator  breadcrumb__pt-310 include-bg p-relative">
      <div class="auto-container">
          <div class="row">
            <div class="col-md-6">
                <div class="breadcrumb__content py-5 p-relative z-index-1">
                  <h5 class="breadcrumb__title">{{ ucfirst($page->getTranslation('title')) }}</h5>
                </div>
            </div>
          </div>
      </div>
    </section>
  </div>
  <!-- banner-section end -->

<!-- account details -->
<div class="style_aboutUsMainDiv__Mo5eY">
    <div class="auto-container">
       <div class="row ac-testimonial-space aos-init" data-aos="fade-up">
            <div class="col-xl-12 col-lg-12 wow tpfadeLeft">
                <div class="ac-testimonial-info">
                   <div class="actestimonial">
                      <div>
                            @php
                                echo $page->getTranslation('content');
                            @endphp
                      </div>
                   </div>
                </div>
             </div>
        </div>
    </div>
 </div>
@endsection
