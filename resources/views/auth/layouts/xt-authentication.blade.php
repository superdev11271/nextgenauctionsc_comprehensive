<!DOCTYPE html>
@php
$rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif
<head>
    @php
        $defaultTitle = get_setting('website_name') . ' | ' . get_setting('site_motto');
        $pageTitle = trim($__env->yieldContent('meta_title', $defaultTitle));
        $seoTitle = seo_title($pageTitle, get_setting('website_name'));
        $pageDescription = trim($__env->yieldContent('meta_description', get_setting('meta_description')));
        $seoDescription = seo_meta_description($pageDescription);
        $canonicalUrl = trim($__env->yieldContent('canonical_url', seo_canonical_url()));
    @endphp

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>{{ $seoTitle }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @yield('meta')

    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
    @php
    $meta_image = uploaded_asset(get_setting('meta_image'));
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $seoTitle }}">
    <meta itemprop="description" content="{{ $seoDescription }}">
    <meta itemprop="image" content="{{ $meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $meta_image }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $seoTitle }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ $meta_image }}" />
    <meta property="og:description" content="{{ $seoDescription }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif
    <!-- Favicon -->
    @php
    $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">

    <meta name="robots" content="index, follow">
    <meta name="description" content="{{ $seoDescription }}" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <script type="application/ld+json">{!! seo_website_schema($seoTitle, $seoDescription, $canonicalUrl) !!}</script>
    @yield('structured_data')

    {{-- <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000, 9999) }}"> --}}


    <link href="{{ static_asset('xt-assets/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/owl.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/color.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/responsive.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/login.css') }}" rel="stylesheet">

    <!-------language script--------->
    <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('
            Nothing selected ', null, true) !!}',
            nothing_found: '{!! translate('
            Nothing found ', null, true) !!}',
            choose_file: '{{ translate('
            Choose file ') }}',
            file_selected: '{{ translate('
            File selected ') }}',
            files_selected: '{{ translate('
            Files selected ') }}',
            add_more_files: '{{ translate('
            Add more files ') }}',
            adding_more_files: '{{ translate('
            Adding more files ') }}',
            drop_files_here_paste_or: '{{ translate('
            Drop files here,
            paste or ') }}',
            browse: '{{ translate('
            Browse ') }}',
            upload_complete: '{{ translate('
            Upload complete ') }}',
            upload_paused: '{{ translate('
            Upload paused ') }}',
            resume_upload: '{{ translate('
            Resume upload ') }}',
            pause_upload: '{{ translate('
            Pause upload ') }}',
            retry_upload: '{{ translate('
            Retry upload ') }}',
            cancel_upload: '{{ translate('
            Cancel upload ') }}',
            uploading: '{{ translate('
            Uploading ') }}',
            processing: '{{ translate('
            Processing ') }}',
            complete: '{{ translate('
            Complete ') }}',
            file: '{{ translate('
            File ') }}',
            files: '{{ translate('
            Files ') }}',
        }
    </script>
    @if (get_setting('google_analytics') == 1)
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ env('TRACKING_ID') }}"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ env('
            TRACKING_ID ') }}');
    </script>
    @endif



    @php $facebookPixelId = trim((string) env('FACEBOOK_PIXEL_ID')); @endphp
    @if (get_setting('facebook_pixel') == 1 && $facebookPixelId !== '')
    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $facebookPixelId }}');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $facebookPixelId }}&ev=PageView&noscript=1" alt="Facebook Pixel Tracking" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    @endif

    @php
    echo get_setting('header_script');
    @endphp

</head>


<!-- page wrapper -->

<body>
    <div class="boxed_wrapper">
        <!-- Preloader -->
        @php
        $user = auth()->user();
        $user_avatar = null;
        $carts = [];
        if ($user && $user->avatar_original != null) {
        $user_avatar = uploaded_asset($user->avatar_original);
        }

        $system_language = get_system_language();

        // if ($user != null) {
        // $carts = App\Models\Cart::where('user_id', auth()->user()->id)->get();
        // }
        @endphp
        <!-- Header -->
        @yield('content')
        @if(!Request::is('login') && !Request::is('shops/create') && !Request::is('seller/login') && !Request::is('email/verify'))
            @include('frontend.inc.xt-footer')
        @endif

        @include('frontend.xthome.modal.xt-cart-model')

    </div>
    <script type="text/javascript">
        function updateFileName(input)
        {
            const fileNameDiv = document.getElementById('file-name');
            if (input.files && input.files[0]) {
                fileNameDiv.textContent = input.files[0].name;
            } else {
                fileNameDiv.textContent = 'Choose File';    
            }
        }
    </script>
    <!-- jequery plugins -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ static_asset('xt-assets/js/jquery.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="{{ static_asset('xt-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ static_asset('xt-assets/js/owl.js') }}"></script>
    <script src="{{ static_asset('xt-assets/js/wow.js') }}"></script>
    <script src="{{ static_asset('xt-assets/js/jquery.fancybox.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-appear/0.1/jquery.appear.min.js"></script>
    <script src="{{ static_asset('xt-assets/js/scrollbar.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js"></script>

    <!-- main-js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>

    <script src="{{ static_asset('xt-assets/js/script.js') }}"></script>
    @yield('modal')

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
    <script src="{{ static_asset('assets/js/aiz-core.js?v=') }}{{ rand(1000, 9999) }}"></script>



    <script>
        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach
    </script>

    <script>


        $(document).ready(function() {

            if ($('#lang-change').length > 0) {
                $('#lang-change a').on('click', function(e){
                    e.preventDefault();
                    var $this = $(this);
                    var locale = $this.data('flag');
                    $.post('{{ route('language.change') }}',{_token: '{{ csrf_token() }}', locale:locale}, function(data){
                        location.reload();
                    });
                });
            }

            if ($('#currency-change').length > 0) {
                $('#currency-change a').on('click', function(e) {
                    e.preventDefault();
                    var $this = $(this);
                    var currency_code = $this.data('currency');
                    $.post('{{ route('currency.change') }}', {_token: '{{ csrf_token() }}', currency_code: currency_code}, function(data) {
                        location.reload();
                    });
                });
            }
        });

        $('#search').on('keyup', function(){
            search();
        });

        $('#search').on('focus', function(){
            search();
        });

        function search(){
            var searchKey = $('#search').val();
            if(searchKey.length > 0){
                $('body').addClass("typed-search-box-shown");

                $('.typed-search-box').removeClass('d-none');
                $('.search-preloader').removeClass('d-none');
                $.post('{{ route('search.ajax') }}', { _token: '{{ csrf_token() }}', search:searchKey}, function(data){
                    if(data == '0'){
                        // $('.typed-search-box').addClass('d-none');
                        $('#search-content').html(null);
                        $('.typed-search-box .search-nothing').removeClass('d-none').html('{{ translate('Sorry, nothing found for') }} <strong>"'+searchKey+'"</strong>');
                        $('.search-preloader').addClass('d-none');

                    }
                    else{
                        $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                        $('#search-content').html(data);
                        $('.search-preloader').addClass('d-none');
                    }
                });
            }
            else {
                $('.typed-search-box').addClass('d-none');
                $('body').removeClass("typed-search-box-shown");
            }
        }


</script>

   @yield('script')

    @php
        echo get_setting('footer_script');
    @endphp



</body>
<!-- End of .page_wrapper -->

</html>
