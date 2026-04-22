<!DOCTYPE html>
@php
    $assets_version = '?v=20240624155744';
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app-url" content="{{ getBaseURL() }}">
    <meta name="file-base-url" content="{{ getFileBaseURL() }}">
    <title>{{ $seoTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
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
        <meta name="twitter:creator"
            content="@author_handle">
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

   <!-- <link href="{{ static_asset('xt-assets/css/flaticon.css') }}{{ $assets_version }}" rel="stylesheet"> -->
   <link href="{{ static_asset('xt-assets/css/owl.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/bootstrap.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/jquery.fancybox.min.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/animate.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/color.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/style.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/responsive.css') }}{{ $assets_version }}" rel="stylesheet">
   <link href="{{ static_asset('xt-assets/css/model.css') }}{{ $assets_version }}" rel="stylesheet">

   <link href="{{ static_asset('xt-assets/libs/slider/css/slick.css') }}{{ $assets_version }}" rel="stylesheet">
   @stack('css')
   @yield('css')
   <!-------language script--------->
   <script>
        var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: '{!! translate('Nothing selected', null, true) !!}',
            nothing_found: '{!! translate('Nothing found', null, true) !!}',
            choose_file: '{{ translate('Choose file') }}',
            file_selected: '{{ translate('File selected') }}',
            files_selected: '{{ translate('Files selected') }}',
            add_more_files: '{{ translate('Add more files') }}',
            adding_more_files: '{{ translate('Adding more files') }}',
            drop_files_here_paste_or: '{{ translate('Drop files here, paste or') }}',
            browse: '{{ translate('Browse') }}',
            upload_complete: '{{ translate('Upload complete') }}',
            upload_paused: '{{ translate('Upload paused') }}',
            resume_upload: '{{ translate('Resume upload') }}',
            pause_upload: '{{ translate('Pause upload') }}',
            retry_upload: '{{ translate('Retry upload') }}',
            cancel_upload: '{{ translate('Cancel upload') }}',
            uploading: '{{ translate('Uploading') }}',
            processing: '{{ translate('Processing') }}',
            complete: '{{ translate('Complete') }}',
            file: '{{ translate('File') }}',
            files: '{{ translate('Files') }}',
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
      gtag('config', '{{ env('TRACKING_ID ') }}');
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


    <div class="loader">
        <div class="loader-container">
            <img src="{{ static_asset('xt-assets/images/loader.svg') }}" alt="Loading..." />
        </div>
    </div>


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
      @include('frontend.xthome.partials.xt-header')
      @yield('content')
      @include('frontend.inc.xt-footer')
      <!-- End of .page_wrapper -->
   <!-- jequery plugins -->
   @yield('modal')

   @if (Auth::guest())
     @include('frontend.xthome.modal.login')
   @endif

   <script src="{{ static_asset('xt-assets/js/jquery.js') }}"></script>
   <script src="{{ static_asset('assets/js/vendors.js') }}"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.43/moment-timezone-with-data.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.0/gsap.min.js"></script>
   <script src="{{ static_asset('xt-assets/js/bootstrap.min.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/owl.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/wow.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/jquery.fancybox.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/scrollbar.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/popper.min.js') }}"></script>
   <script src="{{ static_asset('xt-assets/js/script.js') }}{{ $assets_version }}"></script>
   <script src="{{ static_asset('assets/js/aiz-core.js') }}{{ $assets_version }}"></script>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   @yield('script')


    @php
    $pageScrollKey = 'scrollPosition_' . Route::currentRouteName(); // Assuming route names are unique
    @endphp

    <script>
        const scrollKey = '{{ $pageScrollKey }}';

        window.addEventListener('beforeunload', function() {
            localStorage.setItem(scrollKey, window.scrollY);
        });

        window.addEventListener('load', function() {
            const scrollPosition = localStorage.getItem(scrollKey);
            if (scrollPosition) {
                window.scrollTo(0, scrollPosition);
                localStorage.removeItem(scrollKey);
            }
        });
    </script>

   <script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.getElementById('countdown');
        if (countdownElement) {
            let seconds = parseInt(countdownElement.textContent);

            const countdownInterval = setInterval(() => {
                seconds -= 1;
                countdownElement.textContent = seconds;

                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    countdownElement.parentNode.style.display = 'none';
                }
            }, 1000);
        }
    });
    </script>

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

        @foreach (session('flash_notification', collect())->toArray() as $message)
            AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
        @endforeach


        // Remove fixed 2s blocking delay so first render can appear immediately.
        (function() {
            const hideLoader = function () {
                const loader = document.querySelector('.loader');
                if (loader) {
                    loader.classList.add('d-none');
                }
            };

            if (document.readyState === 'complete') {
                hideLoader();
            } else {
                window.addEventListener('load', hideLoader, { once: true });
                setTimeout(hideLoader, 500);
            }
        })();

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

        function updateNavCart(view,count){
            $('.cart-count').html(count);
            $('#cardRightModal').html(view);
        }

        function removeFromCart(key,page=""){

            $.post('{{ route('cart.removeFromCart') }}', {
                _token  : AIZ.data.csrf,
                id      :  key
            }, function(data){
                if(page=='cart'){
                     location.reload();
                }else{
                    updateNavCart(data.nav_cart_view,data.cart_count);
                }

                AIZ.plugins.notify('success', "{{ translate('Item has been removed from cart') }}");
            });
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                $('#cardRightModal').html(data.nav_cart_view);
                @php if (request()->routeIs('cart')) { @endphp
                    updateQuantity1(key, element);
                @php } @endphp
            });
        }

        function updateQuantity1(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                if(data.product_subtotal && data.user_cart_total){
                    $('[data-id="'+key+'"].ammount-number').text(data.product_subtotal);
                    $('.cart-products-subtotal').text(data.user_cart_total.formated_total);
                    $('.shopping-cart.table input[name="quantity['+key+']"]').val(data.qty);
                    @php if (request()->routeIs('cart')) { @endphp
                        $.post('{{ route('cart.updateQuantity') }}', {
                            _token: AIZ.data.csrf,
                            id: key,
                            quantity: element.value
                        }, function(data) {
                            $('#cardRightModal').html(data.nav_cart_view);
                        });
                    @php } @endphp
                }else{
                   location.reload();
                }
                $('#cart-summary').html(data.cart_view);
            });
        }

        function showLoginModal() {
            $('#login_modal').modal();
        }

        function addToCompare(id){
            $.post('{{ route('compare.addToCompare') }}', {_token: AIZ.data.csrf, id:id}, function(data){

                let compareCountStr = $('#compare_items_count').html();
                let compareCount = parseInt(compareCountStr);
                if (isNaN(compareCount)) {
                    compareCount = 0;
                }

                if(data != 0 && data != 2){
                    $('#compare').html(data);
                    $('.compare-'+id).addClass('text-danger');
                    var updateCompareCount = parseInt(compareCount) + 1;
                    $('#compare_items_sidenav').html('<span id="compare_items_count">'+updateCompareCount+'</span>');
                       AIZ.plugins.notify('success', "{{ translate('Item has been added to compare list') }}");

                       var $element = $('.compare-' + id).closest('a');
                        $element.attr('data-title', 'Added in compare');  // Update the data-title attribute
                        $element.tooltip('dispose');  // Dispose of the existing tooltip

                        setTimeout(function() {
                            $element.tooltip({ title: 'Added in compare' }).tooltip('show');  // Reinitialize and show the new tooltip
                        }, 100);

                    }else if(data == 2){
                        var updateCompareCount = parseInt(compareCount) - 1;
                        $('#compare-'+id).closest('a').attr('data-title', 'add to compare');
                        $('.compare-'+id).removeClass('text-danger')
                        $('#compare_items_sidenav').html('<span id="compare_items_count">'+updateCompareCount+'</span>');
                        $("#compare_"+id).hide();
                        AIZ.plugins.notify('success', "{{ translate('Item has been removed from the compare list') }}");
                        if(updateCompareCount==0){
                            $('#showCompareMsg').html(`
                            <div class="col-xl-12 col-lg-12 col-md-12 py-3">
                                <div class="alert alert-danger text-center">
                                    Your comparison list is empty
                                </div>
                            </div>`);
                        }
                        var $element = $('.compare-' + id).closest('a');
                        $element.attr('data-title', 'Add to compare');
                        $element.tooltip('dispose');
                        setTimeout(function() {
                            $element.tooltip({ title: 'Add to compare' }).tooltip('show');
                        }, 100);

                    }
                });
        }

        function addToWishList(id){
            @if (Auth::check() && (Auth::user()->user_type != 'admin' && Auth::user()->user_type != 'staff' || optional(Auth::user())->shop))
                $.post('{{ route('wishlists.store') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                    if(data == 1){
                        AIZ.plugins.notify('success', "{{ translate('Removed from your watchlist') }}");
                        $(".watchitem"+id).removeClass('fa-solid');
                        var totaItem = $(".main-header #wishlist a span").text();
                        var updateWishlistCount = parseInt(totaItem) - 1;
                        $(".main-header #wishlist a span").text(updateWishlistCount);
                        var $element = $(".watchitem"+id);
                        $element.attr('data-title', 'Add to watchlist');
                        $element.tooltip('dispose').tooltip({ title:  'Add to watchlist' }).tooltip('show');
                    }else if(data == 2){
                        AIZ.plugins.notify('warning', "{{ translate('Already added in watchlist') }}");
                    }else if(data != 0 && data != 1 && data != 2){
                        $('#wishlist').html(data);
                        $(".watchitem"+id).addClass('fa-solid');
                        AIZ.plugins.notify('success', "{{ translate('Item has been added to watchlist') }}");

                        var $element = $(".watchitem"+id);
                        $element.attr('data-title', 'Added in watchlist');
                        // Destroy the old tooltip and create a new one
                        $element.tooltip('dispose').tooltip({ title:  'Added in watchlist' }).tooltip('show');
                    }
                    else{
                        AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
                    }
                });
            @elseif(Auth::check() && Auth::user()->user_type != 'customer')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Watchlist.') }}");
            @else
                AIZ.plugins.notify('warning', "{{ translate('Please login first') }}");
            @endif
        }

        function showAddToCartModal(id){
            if(!$('#modal-size').hasClass('modal-lg')){
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal();
            $('.c-preloader').show();
            $.post('{{ route('cart.showCartModal') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);
                sidebar_cart_view
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });
        }
        $('#option-choice-form input').on('change', function(){
            getVariantPrice();
        });

        function getVariantPrice(){
            if($('#option-choice-form input[name=quantity]').val() > 0 && checkAddToCartValidity()){
                $.ajax({
                    type:"POST",
                    url: '{{ route('products.variant_price') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        $('.product-gallery-thumb .carousel-box').each(function (i) {
                            if($(this).data('variation') && data.variation == $(this).data('variation')){
                                $('.product-gallery-thumb').slick('slickGoTo', i);
                            }
                        })

                        $('#option-choice-form #chosen_price_div').removeClass('d-none');
                        $('#option-choice-form #chosen_price_div #chosen_price').html(data.price);
                        $('#available-quantity').html(data.quantity);
                        $('.input-number').prop('max', data.max_limit);
                        if (data.image !== undefined && data.image !== "") {
                            $('#varient-image-link').prop('href', data.image);
                            $('.varient-image').prop('src', data.image);
                            $('.varient-image').parent().addClass('slick-active slick-current');
                        }

                        if(parseInt(data.in_stock) == 0 && data.digital  == 0){
                           $('.buy-now').addClass('d-none');
                           $('.add-to-cart').addClass('d-none');
                           $('.out-of-stock').removeClass('d-none');
                        }
                        else{
                           $('.buy-now').removeClass('d-none');
                           $('.add-to-cart').removeClass('d-none');
                           $('.out-of-stock').addClass('d-none');
                        }
                        AIZ.extra.plusMinus();
                    }
                });
            }
        }

        function checkAddToCartValidity(){
            var names = {};
            $('#option-choice-form input:radio').each(function() { // find unique names
                names[$(this).attr('name')] = true;
            });
            var count = 0;
            $.each(names, function() { // then count them
                count++;
            });

            if($('#option-choice-form input:radio:checked').length == count){
                return true;
            }

            return false;
        }

        function addToCart(){

            @if (Auth::check() && Auth::user()->user_type != 'customer' && Auth::user()->user_type != 'seller')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                       $('#addToCart-modal-body').html(null);
                       $('#modal-size').removeClass('modal-lg');
                       $('#addToCart-modal-body').html(data.modal_view);
                       $('#cardRightModal').html(data.xt_cart_view);
                       if(data.status == 1){
                           $('.cart-count').html(data.cart_count);
                       }
                       AIZ.extra.plusMinus();
                       AIZ.plugins.slickCarousel();
                       $('#addToCart').modal('show');
                    }
                });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function buyNow(){
            @if (Auth::check() && Auth::user()->user_type != 'customer' && Auth::user()->user_type != 'seller')
                AIZ.plugins.notify('warning', "{{ translate('Please Login as a customer to add products to the Cart.') }}");
                return false;
            @endif

            if(checkAddToCartValidity()) {
                $('#addToCart-modal-body').html(null);
                $('#addToCart').modal();
                $('.c-preloader').show();
                $.ajax({
                    type:"POST",
                    url: '{{ route('cart.addToCart') }}',
                    data: $('#option-choice-form').serializeArray(),
                    success: function(data){
                        if(data.status == 1){
                            $('#addToCart-modal-body').html(data.modal_view);
                            updateNavCart(data.nav_cart_view,data.cart_count);
                            window.location.replace("{{ route('cart') }}");
                        }
                        else{
                            $('#addToCart-modal-body').html(null);
                            $('.c-preloader').hide();
                            $('#modal-size').removeClass('modal-lg');
                            $('#addToCart-modal-body').html(data.modal_view);
                        }
                    }
               });
            }
            else{
                AIZ.plugins.notify('warning', "{{ translate('Please choose all the options') }}");
            }
        }

        function bid_single_modal(bid_product_id, min_bid_amount){
            @if (Auth::check() && (isCustomer() || isSeller()))
                var min_bid_amount_text = "({{ translate('Min Bid Amount: ') }}"+min_bid_amount+")";
                $('#min_bid_amount').text(min_bid_amount_text);
                $('#bid_product_id').val(bid_product_id);
                $('#bid_amount').attr('min', min_bid_amount);
                $('#bid_for_product').modal('show');
            @elseif (Auth::check() && isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function clickToSlide(btn,id){
            $('#'+id+' .aiz-carousel').find('.'+btn).trigger('click');
            $('#'+id+' .slide-arrow').removeClass('link-disable');
            var arrow = btn=='slick-prev' ? 'arrow-prev' : 'arrow-next';
            if ($('#'+id+' .aiz-carousel').find('.'+btn).hasClass('slick-disabled')) {
                $('#'+id).find('.'+arrow).addClass('link-disable');
            }
        }

        function goToView(params) {
            document.getElementById(params).scrollIntoView({behavior: "smooth", block: "center"});
        }

        function copyCouponCode(code){
            navigator.clipboard.writeText(code);
            AIZ.plugins.notify('success', "{{ translate('Coupon Code Copied') }}");
        }

        $(document).ready(function(){
            $('.cart-animate').animate({margin : 0}, "slow");

            $({deg: 0}).animate({deg: 360}, {
                duration: 2000,
                step: function(now) {
                    $('.cart-rotate').css({
                        transform: 'rotate(' + now + 'deg)'
                    });
                }
            });

            setTimeout(function(){
                $('.cart-ok').css({ fill: '#d43533' });
            }, 2000);

            $('#cardRightModal').on('show.bs.offcanvas', function() {
                //  add action before open right sidebar cart
            });
        });





    @if (addon_is_activated('otp_system'))

            // Country Code
            var isPhoneShown = true,
                countryData = window.intlTelInputGlobals.getCountryData(),
                input = document.querySelector("#phone-code");

            for (var i = 0; i < countryData.length; i++) {
                var country = countryData[i];
                if (country.iso2 == 'bd') {
                    country.dialCode = '88';
                }
            }

            var iti = intlTelInput(input, {
                separateDialCode: true,
                utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
                onlyCountries: @php echo get_active_countries()->pluck('code') @endphp,
                customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                    if (selectedCountryData.iso2 == 'bd') {
                        return "01xxxxxxxxx";
                    }
                    return selectedCountryPlaceholder;
                }
            });

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

            input.addEventListener("countrychange", function(e) {
                // var currentMask = e.currentTarget.placeholder;
                var country = iti.getSelectedCountryData();
                $('input[name=country_code]').val(country.dialCode);

            });

            function toggleEmailPhone(el) {
                if (isPhoneShown) {
                    $('.phone-form-group').addClass('d-none');
                    $('.email-form-group').removeClass('d-none');
                    $('input[name=phone]').val(null);
                    isPhoneShown = false;
                    $(el).html('*{{ translate('Use Phone Number Instead') }}');
                } else {
                    $('.phone-form-group').removeClass('d-none');
                    $('.email-form-group').addClass('d-none');
                    $('input[name=email]').val(null);
                    isPhoneShown = true;
                    $(el).html('<i>*{{ translate('Use Email Instead') }}</i>');
                }
            }
        @endif


        var acc = document.getElementsByClassName("aiz-accordion-heading");
        var i;
        for (i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.maxHeight) {
                    panel.style.maxHeight = null;
                } else {
                    panel.style.maxHeight = panel.scrollHeight + "px";
                }
            });
        }
    </script>
    @include('frontend.xthome.modal.xt-add-to-cart-model')
    @include('frontend.xthome.modal.xt-cart-model')
    @include('frontend.xthome.modal.script')
    @include('frontend.xthome.modal.bid-script')
<script>
    function show_chat_modal(){
        @if (Auth::check())
            $('#chat_modal').modal('show');
        @else
            $('#login_modal').modal('show'); @endif
    }

    $(function() {
        if ($(window).width() > 1024) {
            $('.category__menu__item').hover(function() {
                $(this).parent('.category__menu__list').find('.category__menu__item').removeClass(
                    'active');
                $(this).addClass('active');
            })
        }
    });

    $(function() {
        if ($(window).width() < 1024) {
            $('.category__menu__item').click(function() {
                $(this).parent('.category__menu__list').find('.category__menu__item').removeClass(
                    'active');
                $(this).addClass('active');
            })
        }
    });
    $(document).ready(function() {
        $('select').on('mouseover', 'option', function() {
            var title = $(this).attr('title');
            if (title) {
                $(this).attr('data-toggle', 'tooltip').tooltip('show');
            }
        }).on('mouseout', 'option', function() {
            $(this).tooltip('hide');
        });
    });



    const countdownTimers = document.querySelectorAll('.countdown-timer');
    
    countdownTimers.forEach(timer => {
        const productId = timer.dataset.id;
        const endDate = new Date(timer.dataset.end).getTime();
    
        function updateCountdown() {
            const now = Date.now();
            const timeLeft = endDate - now;
    
            
            if (timeLeft <= 0) {
                document.getElementById(`countdown-display-${productId}`).textContent = "ended.";
                clearInterval(timerInterval); 
                return;
            }
    
          
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
    
            
            document.getElementById(`countdown-display-${productId}`).textContent =
                `${days > 0 ? days + ' d :' : ''}${hours} h: ${minutes} m: ${seconds} s`;
        }
    
       
        const timerInterval = setInterval(updateCountdown, 1000);
    
        updateCountdown();
    });
    
  
</script>

@stack('js')

@yield('scriptjs')
@php
    echo get_setting('footer_script');
@endphp

@if(get_setting('pusher_status') == 1)
    @include("frontend.layouts.pusherjs");
@endif
</body>
@stack('scripts')
<script type="text/javascript">
    const publicVapidKey =  '{{env('VAPID_PUBLIC_KEY')}}'
    const subscribeRoute =  '{{route('subscribe.notification')}}'
    const workerjs =  "{{static_asset('notification_service_worker.js')}}"
</script>
<script type="text/javascript" src="{{static_asset('xt-assets/js/notification_subscribe.js')}}"></script>
</html>
