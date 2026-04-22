@extends('frontend.layouts.xt-app')

@push('css')
    <link href="{{static_asset('xt-assets')}}/css/blog.css" rel="stylesheet">
@endpush

@section('meta_title'){{ $blog->meta_title }}@stop

@section('meta_description'){{ $blog->meta_description }}@stop

@section('meta_keywords'){{ $blog->meta_keywords }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $blog->meta_title }}">
    <meta itemprop="description" content="{{ $blog->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $blog->meta_title }}">
    <meta name="twitter:description" content="{{ $blog->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($blog->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $blog->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('blog.details', $blog->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($blog->meta_img) }}" />
    <meta property="og:description" content="{{ $blog->meta_description }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')




    <!-- banner-section end -->


    <!-- blog details -->
    <div class="pt-5">
        <div class='auto-container'>
            <div class='row'>
                <div class='col-xl-8 col-lg-8'>
                    <div class="postbox__wrapper pr-20" data-aos="fade-up">
                        <div class="row">
                            <div class="col-xl-12 col-sm-12">
                                <div class="postbox__details">
                                    {{-- <a href="/blog-details/19"> --}}
                                        <img src={{ uploaded_asset($blog->banner) }} alt="{{ $blog->title }}"  class="news-details" />
                                    {{-- </a> --}}
                                </div>
                            </div>
                            <div class="col-xl-12 col-sm-12">
                                <div class="postbox__content pt-4">
                                    <div class="postbox__meta">
                                        {{-- <span>
                                            <a href="#"><i class="fa-regular fa-circle-user"></i> LOREM </a>
                                        </span> --}}
                                        <span>
                                            {{-- <a href="#"> --}}
                                                <i class="fa-regular fa-clock"></i>{{ date('M d, Y',strtotime($blog->created_at)) }}
                                            {{-- </a> --}}
                                        </span>
                                        {{-- <span>
                                            <a href="#"><i class="fa-regular fa-comment-dots"></i> (04) Coments</a>
                                        </span> --}}
                                        {{-- @if($blog->category != null)
                                        <span>
                                            <a href="#"><i class="fa-regular fa-eye"></i>{{ $blog->category->category_name }}</a>
                                        </span>
                                        @endif --}}
                                    </div>
                                    <h3>{{ $blog->title }}</h3>
                                    <div class="postbox__text">
                                        {!! $blog->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='row' data-aos="fade-up">
                        <div class='col-lg-6'>
                            <img src='assets/images/blog-big-4.jpg' alt='' />
                        </div>
                        <div class='col-lg-6'>
                            <img src='assets/images/blog-sm-5.jpg' alt='' />
                        </div>
                    </div>
                    <div class="postbox__social-wrapper mt-5" data-aos="fade-up">
                        <div class="row">
                            <div class="col-xl-6 col-lg-12">
                                <div class="postbox__tag tagcloud">
                                    <span>Tag</span>
                                    @foreach($blog_categories as $categories)
                                        <a href="#!">{{$categories->category_name}}</a>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-12">
                                <div class="postbox__social text-xl-end text-start">
                                    <span>Share</span>

                                    <div class="aiz-share"></div>


                                    {{-- <a href="https://www.linkedin.com/" target="_blank" rel="noreferrer">
                                        <i class="fab fa-linkedin tp-linkedin"></i>
                                    </a>
                                    <a href="https://www.pinterest.com/" target="_blank" rel="noreferrer">
                                        <i class="fab fa-pinterest tp-pinterest"></i>
                                    </a>
                                    <a href="https://www.facebook.com/" target="_blank" rel="noreferrer">
                                        <i class="fab fa-facebook tp-facebook"></i>
                                    </a>
                                    <a href="https://twitter.com/" target="_blank" rel="noreferrer">
                                        <i class="fab fa-twitter tp-twitter"></i>
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class='col-xl-4 col-lg-4' data-aos="fade-up">
                    <div class='StickySection'>
                        <div class='sidebar__wrapper'>
                            <div class="sidebar__widget mb-40">
                                <h3 class="sidebar__widget-title">{{ translate('Recent Posts') }}</h3>
                                <div class="sidebar__widget-content">
                                    <div class="sidebar__post rc__post">
                                        @foreach($recent_blogs as $recent_blog)
                                        <div class="rc__post mb-20 d-flex align-items-center">
                                            <div class="rc__post-thumb mr-20">
                                                <a href="{{ url("news").'/'. $recent_blog->slug }}">
                                                    <img src={{ uploaded_asset($recent_blog->banner) }} alt="{{ $recent_blog->title }}" />
                                                </a>
                                            </div>
                                            <div class="rc__post-content">
                                                <div class="rc__meta"><span>{{ date('M d, Y',strtotime($recent_blog->created_at)) }}</span></div>
                                                <h3 class="rc__post-title">
                                                    <a href="{{ url("news").'/'. $recent_blog->slug }}">{{ $recent_blog->title }}</a>
                                                </h3>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection




