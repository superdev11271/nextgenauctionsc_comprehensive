<!-- search-popup -->

@php
    $topbar_banner = get_setting('topbar_banner');
    $topbar_banner_medium = get_setting('topbar_banner_medium');
    $topbar_banner_small = get_setting('topbar_banner_small');
    $topbar_banner_asset = uploaded_asset($topbar_banner);
    $header_logo = get_setting('header_logo');
@endphp

<!-------Main Header------------>

<div id="search-popup" class="search-popup">
    <div class="close-search"><i class="fa fa-close"></i></div>
    <div class="popup-inner">
        <div class="overlay-layer"></div>
        <div class="search-form">
            <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                <div class="form-group">
                    <fieldset>
                        <input type="search" class="form-control" id="search"
                            name="keyword"@isset($query) value="{{ $query }}" @endisset
                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">
                        <input type="submit" id="header_search_submit" name="header_search_submit" value="Search Now!" class="theme-btn style-four">
                    </fieldset>
                </div>
            </form>

            <div
                class="typed-search-box stop-propagation document-click-d-none d-none rounded shadow-lg position-absolute left-0 top-100 w-100 min-heght-100">
                <div class="search-preloader absolute-top-center">
                    <div class="dot-loader">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="search-nothing d-none p-3 text-center fs-16">
                </div>
                <div id="search-content" class="text-left">
                </div>
            </div>
        </div>
    </div>
</div>
<header class="main-header">
    <div class="header-top d-none d-lg-block">
        @if (get_setting('header_note'))
            <div class="ticker_content">
                <div class="auto-container overflow-hidden">
                    <div>
                        <marquee id="marquee">{{ get_setting('header_note') }}</marquee>
                    </div>
                </div>
            </div>
        @endif
        <div class="auto-container">
            <div class="top-inner clearfix d-none d-lg-block">
                <div class="top-left pull-left">
                    <ul class="info clearfix">
                        @if (get_setting('header_email'))
                            <li><i class="fa-solid fa-envelope"></i><a
                                    href="mailto:{{ get_setting('header_email') }}">{{ get_setting('header_email') }}</a>
                            </li>
                        @endif
                        @if (get_setting('helpline_number'))
                            <li><i class="fa-solid fa-phone"></i><a href="tel:{{ get_setting('helpline_number') }}">{{ get_setting('helpline_number') }}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="top-right pull-right">
                    @if (Auth::check())
                        @if (Auth::user()->user_type == 'customer')
                            @if (!Auth::user()->shop)
                                <ul class="social-links clearfix">
                                    <li>
                                        <a href="{{ route('shops.create') }}"
                                            class="text-secondary fs-12 pr-3 d-inline-block border-width-2 border-right">{{ __('Become Seller') }}</a>
                                    </li>
                                </ul>
                            @endif
                        @endif
                    @else
                        <ul class="social-links clearfix">
                            <li>
                                <a href="{{ route('shops.create') }}"
                                    class="text-secondary fs-12 pr-3 d-inline-block border-width-2 border-right">{{ __('Seller Registration') }}</a>
                            </li>
                        </ul>
                        <ul class="social-links clearfix">
                            <li>
                                <a href="{{ route('seller.login') }}"
                                    class="text-secondary fs-12 pr-3 d-inline-block border-width-2 border-right">{{ __('Seller Login') }}</a>
                            </li>
                        </ul>
                    @endif
                    <!-- Currency Switcher -->
                    @if (get_setting('show_currency_switcher') == 'on')
                        @php
                            $system_currency = get_system_currency();
                        @endphp
                        <div class="price-box dropdown pt-1">
                            <span data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $system_currency->name ?? '' }}</span>
                            <ul class="clearfix dropdown-menu drop_lang" id="currency-change">
                                @foreach (get_all_active_currency() as $key => $currency)
                                    <li class="py-2"><a data-currency="{{ $currency->code }}"
                                            href="javascript:void(0)">{{ $currency->name }}
                                            ({{ $currency->symbol }})
                                        </a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="header-lower">
        <div class="auto-container">
            <div class="outer-box">
                @if ($header_logo != null)
                    <figure class="logo-box"><a href="{{ route('home') }}"><img width="60px"
                                src="{{ uploaded_asset($header_logo) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="{{ env('APP_NAME') }}"></a></figure>
                @else
                    <figure class="logo-box"><a href="{{ route('home') }}"><img width="60px"
                                src="{{ static_asset('assets/img/logo.png') }}"  onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="{{ env('APP_NAME') }}"></a>
                    </figure>
                @endif
                <div class="menu-area">
                    <div class="mobile-nav-toggler">
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                        <i class="icon-bar"></i>
                    </div>
                    <nav class="main-menu navbar-expand-md navbar-light">
                        <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                            <ul class="navigation clearfix">
                                <li class="@if (Route::currentRouteName() == 'home') current @endif">
                                    <a href="{{ route('home') }}">
                                        {{ translate('Home') }}</a>
                                </li>
                                @include('frontend.xthome.partials.megamenu', [
                                    'title' => 'Auctions',
                                    'categories' => $parent_categories,
                                    'menu_route' => 'auction_collection',
                                    'category_route' => 'auction.products.category',
                                ])
                                {{-- @include('frontend.xthome.partials.megamenu', [
                                    'title' => 'Upcoming Auctions',
                                    'categories' => $parent_categories,
                                    'menu_route' => 'upcoming_auction_collection',
                                    'category_route' => 'upcoming-auction.products.category',
                                ]) --}}
                                @include('frontend.xthome.partials.megamenu', [
                                    'title' => 'Marketplace',
                                    'categories' => $parent_categories,
                                    'menu_route' => 'marketplace',
                                    'category_route' => 'products.category',
                                ])

                                @if (get_setting('header_menu_labels') != null)
                                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                        <li class="@if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) current @endif"><a
                                                href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}">{{ translate($value) }}</a>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                    </nav>
                </div>
                <ul class="menu-right-content clearfix">
                    <li>
                        <div class="search-btn">
                            <button type="button" class="search-toggler"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </li>
                    <li class="shop-cart d-none d-lg-block" id="comparelist">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.compare')
                    </li>
                    <li class="shop-cart d-none d-lg-block" id="wishlist">
                        @include('frontend.' . get_setting('homepage_select') . '.partials.wishlist')
                    </li>

                    @if ($notificationCount)
                        <li class="shop-cart" id="notification">
                            <a href="{{ route('bidded_products', "notification") }}" class="d-flex align-items-center"
                                data-toggle="tooltip" data-title="{{ translate('Chat Notification') }}"
                                data-placement="top">
                                <i class="fa fa-commenting" aria-hidden="true"></i>
                                    <span>{{ $notificationCount }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="shop-cart d-none d-lg-block">
                        <a data-bs-toggle="offcanvas" href="#cardRightModal" role="button"
                            aria-controls="cardRightModal"><i class="fa-solid fa-cart-shopping"></i><span
                                class="cart-count"> @include('frontend.' . get_setting('homepage_select') . '.partials.cart')</span></a>
                    </li>
                    @auth
                        <li class="dropdown">
                            <a class="btn btn-dark" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-regular fa-user"></i>
                            </a>
                            <ul class="dropdown-menu">
                                @if (isAdmin())
                                    <li><a class="dropdown-item"
                                            href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i> {{ translate('My Account') }}</a></li>
                                @elseif(isSeller())
                                    <li><a class="dropdown-item"
                                            href="{{ route('seller.dashboard') }}"><i class="fa fa-home"></i> {{ translate('Dashbord') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('dashboard') }}"> <i class="fa fa-user"></i>  {{ translate('My Account') }}</a></li>
                                @endif
                                @if (isCustomer())
                                    @if(!auth()->user()?->shop)
                                    <li><a class="dropdown-item"
                                            href="{{ route('dashboard') }}"><i class="fa fa-user"></i> {{ translate('My Account') }}</a></li>
                                    @endif
                                    <li><a class="dropdown-item" href="{{ route('all-notifications') }}"><i class="fa fa-bell"></i> {{ translate('All Notifications') }}
                                        @if(Auth::user()?->unreadNotifications->count() > 0)
                                        <div class="ms-auto badge-pill badge bg-secondary">{{Auth::user()->unreadNotifications->count()}}</div>
                                        @endif
                                    </a> </li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('compare') }}"><i class="fa fa-code-compare"></i> {{ translate('Compare') }}</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('wishlists.auction') }}"><i class="fa fa-heart"></i> {{ translate('My Watchlist') }}</a>
                                    </li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> {{ translate('Logout') }}</a>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="login-text">
                            <a href="{{ route('user.login') }}">Sign in</a> <span class="login-text-line">|</span> <a
                                href="{{ route('user.registration') }}">Register</a>
                        </li>
                    @endAuth
                </ul>
            </div>
        </div>
    </div>
    <div class="sticky-header">
        <div class="auto-container">
            <div class="outer-box clearfix">
                <div>
                    @if ($header_logo != null)
                        <div class="logo-box pull-left">
                            <figure class="logo"><a href="{{ route('home') }}"><img
                                        width="60px"src="{{ uploaded_asset($header_logo) }}"
                                        alt="{{ env('APP_NAME') }}"  onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></a></figure>
                        </div>
                    @else
                        <div class="logo-box pull-left">
                            <figure class="logo"><a href="{{ route('home') }}"><img width="60px"
                                        src="{{ static_asset('assets/img/logo.png') }}"
                                        alt="{{ env('APP_NAME') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></a></figure>
                        </div>
                    @endif
                </div>
                <div class="menu-area pull-right">
                    <nav class="main-menu clearfix">
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- main-header end -->

<!-- Mobile Menu  -->
<div class="mobile-menu">
    <div class="menu-backdrop"></div>
    <div class="close-btn"><i class="fas fa-times"></i></div>
    <nav class="menu-box">
        @if ($header_logo != null)
            <div class="nav-logo"><a href="{{ route('home') }}"><img width="60px" src="{{ uploaded_asset($header_logo) }}"
                        alt="{{ env('APP_NAME') }}" title=""></a></div>
        @else
            <div class="nav-logo"><a href="{{ route('home') }}"><img
                        src="{{ static_asset('assets/img/logo.png') }}"
                        width="60px"
                        alt="{{ env('APP_NAME') }}"
                        title=""></a></div>
        @endif
        <div class="contact-info px-4 py-0">
            <ul class="pt-4 mobile-nav d-flex gap-3">
                <li class="shop-cart">
                    @include('frontend.' . get_setting('homepage_select') . '.partials.compare')
                </li>
                <li class="shop-cart" id="wishlist">
                    @include('frontend.' . get_setting('homepage_select') . '.partials.wishlist')
                </li>

                <li class="shop-cart">
                    <a data-bs-toggle="offcanvas" href="#cardRightModal" role="button"
                        aria-controls="cardRightModal"><i class="fa-solid fa-cart-shopping"></i><span
                            class="cart-count"> @include('frontend.' . get_setting('homepage_select') . '.partials.cart')</span></a>
                </li>
            </ul>
        </div>
        <div class="menu-outer">
            <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
        </div>

        <div>
            <ul>
                @if (get_setting('vendor_system_activation') == 1)
                    @if(!Auth::check())
                    <li class="px-4 py-2 border-bottom fw-500"><a href="{{ route('shops.create') }}" class="text-white">{{ translate('Become a Seller !') }}</a></li>
                    <li class="px-4 py-2 border-bottom"><a href="{{ route('seller.login') }}" class="text-white">{{ translate('Login to Seller') }}</a></li>
                    @endif
                @endif
            </ul>
        </div>

        <div class="contact-info px-4 py-2 border-bottom">
            <!-- Currency Switcher -->
            @if (get_setting('show_currency_switcher') == 'on')
                @php
                    $system_currency = get_system_currency();
                @endphp
                <div class="price-box dropdown">
                    <span data-bs-toggle="dropdown" aria-expanded="false">
                        {{ $system_currency->name ?? '' }} <i class="fa-solid fa-chevron-down fs-13"></i></span>
                    <ul class="clearfix dropdown-menu drop_lang" id="currency-change">
                        @foreach (get_all_active_currency() as $key => $currency)
                            <li class="py-1 fs-13"><a data-currency="{{ $currency->code }}"
                                    href="javascript:void(0)">{{ $currency->name }}
                                    ({{ $currency->symbol }})
                                </a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="contact-info">
            <h4>Contact Info</h4>
            <ul>
                @if (get_setting('helpline_number'))
                    <li>
                        <a href="tel:{{ get_setting('helpline_number') }}">
                            <span>{{ translate('Helpline') }}</span>
                            <span>{{ get_setting('helpline_number') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>


        <div class="social-links">
            <ul class="clearfix">
                @if (get_setting('show_social_links'))
                    @if (!empty(get_setting('facebook_link')))
                        <li><a href="{{ get_setting('facebook_link') }}"><i class="fab fa-facebook-f"></i></a></li>
                    @endif
                    @if (!empty(get_setting('twitter_link')))
                        <li><a href="{{ get_setting('twitter_link') }}"><i class="fab fa-twitter"></i></a></li>
                    @endif
                    @if (!empty(get_setting('instagram_link')))
                        <li><a href="{{ get_setting('instagram_link') }}"><i class="fab fa-instagram"></i></a></li>
                    @endif
                    @if (!empty(get_setting('youtube_link')))
                        <li><a href="{{ get_setting('youtube_link') }}"><i class="fab fa-vimeo-v"></i></a></li>
                    @endif
                    @if (!empty(get_setting('linkedin_link')))
                        <li><a href="{{ get_setting('linkedin_link') }}"><i class="fab fa-linkedin-in"></i></a></li>
                    @endif
                @endif
            </ul>
        </div>
    </nav>
</div>
<!-- End Mobile Menu -->

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }
    </script>
@endsection
