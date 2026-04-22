<!-- main-footer -->
@php
$header_logo = get_setting('header_logo');
$get_static_pages =  get_static_pages();
@endphp
</div>
<footer class="main-footer">
    <div class="footer-top">
        <div class="auto-container">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12 col-sm-12 big-column">
                    <div class="row clearfix">
                        <div class="col-lg-4 col-md-4 col-sm-12 footer-column">
                            <div class="footer-widget logo-widget">
                                @if ($header_logo != null) 
                                <figure class="footer-logo"><a href="{{ route('home') }}"><img  src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}" ></a></figure>
                                @else
                                <figure class="footer-logo"><a href="{{ route('home') }}"><img width="60px" src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}" ></a></figure>
                                @endif
                            </div>
                            <ul class="footer-social clearfix mt-3">
                                    @if ( get_setting('show_social_links') )
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
                                    <li><a href="{{ get_setting('youtube_link') }}"><i class="fab fa-youtube"></i></a></li>
                                    @endif
                                    @if (!empty(get_setting('linkedin_link')))
                                    <li><a href="{{ get_setting('linkedin_link') }}"><i class="fab fa-linkedin-in"></i></a></li>
                                    @endif
                                    @endif
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 footer-column">
                            <div class="footer-widget links-widget">
                                <div class="widget-title">
                                    <h3>{{ translate('My Account') }}</h3>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        @if (Auth::check())
                                        <li> <i class="fa fa-power-off"></i> <a href="{{ route('logout') }}">{{ translate('Logout') }}</a></li>
                                        <li><a href="{{ route('purchase_history.index') }}">{{ translate('Order History') }}</a></li>
                                        <li><a href="{{ route('wishlists.auction') }}">{{ translate('My Watchlist') }}</a></li>
                                        @else
                                        <li><a href="{{ route('user.login') }}"> {{ translate('Login') }}</a></li>
                                        @endif
                                        
                                        @if(Auth::check() && Auth::user()->user_type != 'admin')
                                        <li><a href="{{ route('purchase_history.index') }}">{{ translate('Track Order') }}</a></li>
                                        @else
                                        <li><a href="{{ route('orders.track') }}">{{ translate('Track Order') }}</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 footer-column">
                            <div class="footer-widget links-widget">
                                <div class="widget-title">
                                    <h3>{{ translate('Useful Link') }}</h3>
                                </div>
                                <div class="widget-content">
                                    <ul class="links-list clearfix">
                                        @if (get_setting('header_menu_labels') != null)
                                        @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                                            <li><a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] ?? '' }}">{{ translate($value) }}</a></li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 big-column">
                    <div class="row clearfix">
                        <div class="col-lg-6 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget contact-widget">
                                <div class="widget-title">
                                    <h3>Other Link</h3>
                                </div>
                                <ul class="info-list clearfix">
                                    <li><a href="{{ route('home') }}">{{ translate('home') }}</a></li>
                                    @foreach($get_static_pages as $page)
                                       
                                        @if($page->slug != 'home')
                                        <li><a href="{{ route('staticPages', $page->slug) }}">{{ translate($page->title) }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 footer-column">
                            <div class="footer-widget newsletter-widget">
                                <div class="widget-title">
                                    <h3>{{ translate('Address') }}</h3>
                                </div>
                                <div class="widget-content">
                                    <p>{!! get_setting('contact_address',null,App::getLocale()) !!}</p>
                                    <li><a href="tel:{{ get_setting('helpline_number') }}" title="{{ translate('Phone') }}">{{ get_setting('helpline_number') }}</a></li>
                                    <li><a href="mailto:{{ get_setting('contact_email') }}" title="{{ translate('Email') }}">{{ get_setting('contact_email') }} </a></li>
                                    <form method="POST" action="{{ route('subscribers.store') }}" class="newsletter-form mt-3">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input type="email" class="form-control" required name="email" placeholder="{{ translate('Your Email Address') }}" />
                                            <button type="submit" class="input-group-text theme-btn-card" id="basic-addon2">{{ translate('Subscribe') }}</button>
                                        </div>
                                        <!-- <div class="form-group d-flex"><input type="email" required name="email" placeholder="{{ translate('Your Email Address') }}"><button type="submit" class="theme-btn-two px-2 p-0">{{ translate('Subscribe') }}</button></div> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="auto-container clearfix">
            <ul class="cart-list pull-left clearfix">
                @if ( get_setting('payment_method_images') != null )
                @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                <li><a >
                        <img src="{{ uploaded_asset($value) }}" alt="{{ translate('payment_method') }}" class="payment_card">
                    </a>
                </li>
                @endforeach
                @endif
            </ul>
            <div class="copyright pull-right">
                <p>{!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}</p>
            </div>
        </div>
    </div>
</footer>
<!-- main-footer end -->


<!--Scroll to top-->
<button class="scroll-top scroll-to-target" data-target="html">
    <i class="fas fa-long-arrow-alt-up"></i>
</button>
