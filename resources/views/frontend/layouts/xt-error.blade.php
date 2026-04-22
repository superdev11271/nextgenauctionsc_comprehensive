<!DOCTYPE html>
<html lang="en">
@php
$rtl = get_session_language()->rtl;
@endphp

@if ($rtl == 1)
<html dir="rtl" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endif

<head>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <title>@yield('meta_title', get_setting('website_name') . ' | ' . get_setting('site_motto'))</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @yield('meta')

    @if (!isset($detailedProduct) && !isset($customer_product) && !isset($shop) && !isset($page) && !isset($blog))
    @php
    $meta_image = uploaded_asset(get_setting('meta_image'));
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ get_setting('meta_title') }}">
    <meta itemprop="description" content="{{ get_setting('meta_description') }}">
    <meta itemprop="image" content="{{ $meta_image }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ get_setting('meta_title') }}">
    <meta name="twitter:description" content="{{ get_setting('meta_description') }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $meta_image }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ get_setting('meta_title') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('home') }}" />
    <meta property="og:image" content="{{ $meta_image }}" />
    <meta property="og:description" content="{{ get_setting('meta_description') }}" />
    <meta property="og:site_name" content="{{ env('APP_NAME') }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    @endif
    <!-- Favicon -->
    @php
    $site_icon = uploaded_asset(get_setting('site_icon'));
    @endphp
    <link rel="icon" href="{{ $site_icon }}">

    <meta name="robots" content="index, follow">
    <meta name="description" content="@yield('meta_description', get_setting('meta_description'))" />
    <meta name="keywords" content="@yield('meta_keywords', get_setting('meta_keywords'))">

    <link rel="stylesheet" href="{{ static_asset('assets/css/aiz-core.css?v=') }}{{ rand(1000, 9999) }}">


    <link href="{{ static_asset('xt-assets/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/owl.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/color.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ static_asset('xt-assets/css/responsive.css') }}" rel="stylesheet">
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

    </div>

</body>
<!-- End of .page_wrapper -->

</html>
