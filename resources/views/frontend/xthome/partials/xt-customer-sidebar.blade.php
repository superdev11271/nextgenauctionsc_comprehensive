@php
    $user = auth()->user();
    $user_avatar = null;
    $carts = [];
    if ($user && $user->avatar_original != null) {
        $user_avatar = uploaded_asset($user->avatar_original);
    }
@endphp

<div class="col-lg-4 pb-4 pb-lg-0 col-xxl-3 pe-xxl-4">
    <div class="light-darg-bg border border-bottom-0 shadow-sm acc-side border-bottom">
        <div class="pt-3">
            <div class="d-flex justify-content-center">

                <div class="avatar avatar-lg rounded-circle">
                    @if ($user->avatar_original != null)
                    <div class="position-relative d-inline-block">
                        <img src="{{ $user_avatar }}" class="img-fluid"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @if(auth()->user()->shop?->premium)
                        <i class="fa fa-crown text-primary position-absolute top-0 end-0" title="{{__('Premium User')}}"></i>
                        @endif
                    </div>

                    @else
                    <div class="position-relative d-inline-block">
                        <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                            @if(auth()->user()->shop?->premium)
                        <i class="fa fa-crown text-primary position-absolute top-0 end-0" title="{{__('Premium User')}}"></i>
                        @endif
                    </div>
                    @endif

                    {{-- <div class="cam">
                        <label for="image_file">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M40.8121 1.32129C39.9601 0.46875 38.8297 0 37.6285 0C36.4273 0 35.2965 0.469727 34.4449 1.32129L29.1534 6.6123L6.38535 29.3809C6.3845 29.3817 6.38328 29.382 6.38243 29.3828C6.38157 29.3837 6.38133 29.3849 6.38047 29.3857C6.38053 29.3857 6.38041 29.3858 6.38047 29.3857C6.2786 29.4884 6.20671 29.6082 6.15849 29.7351C6.1558 29.7421 6.14915 29.7468 6.14659 29.7539L0.0591833 46.6611C-0.0721644 47.0254 0.0191443 47.4336 0.29307 47.707C0.483988 47.8975 0.739359 48 1.0001 48C1.11387 48 1.22813 47.9805 1.33897 47.9404L18.2462 41.8535C18.2538 41.8508 18.2588 41.8437 18.2663 41.8408C18.3771 41.7985 18.4783 41.7306 18.5711 41.6475C18.584 41.6359 18.6022 41.6324 18.6144 41.6201L41.3878 18.8467L46.6788 13.5557C47.5309 12.7041 48.0001 11.5732 48.0001 10.3721C48.0001 9.16992 47.5309 8.04004 46.6788 7.18848L40.8121 1.32129ZM6.68614 41.3135C6.07902 40.7064 5.38389 40.2358 4.62028 39.8967L7.49589 31.9103L16.09 40.5045L8.10319 43.3796C7.76316 42.6146 7.29252 41.9195 6.68614 41.3135ZM8.50401 30.0903L29.8605 8.7334L33.8565 12.7295L12.4996 34.0859L8.50401 30.0903ZM3.94193 41.7807C4.42979 42.0158 4.87614 42.3316 5.27207 42.7275C5.66734 43.1228 5.98344 43.569 6.21934 44.0578L2.66075 45.3389L3.94193 41.7807ZM17.91 39.4963L13.9137 35.5L35.2706 14.1436L39.2667 18.1396L17.91 39.4963ZM45.2648 12.1416L40.6808 16.7256L31.2745 7.31934L35.859 2.73535C36.8063 1.78613 38.4488 1.78516 39.3981 2.73535L45.2648 8.60254C45.7389 9.07617 46.0001 9.70508 46.0001 10.3721C46.0001 11.0391 45.7389 11.667 45.2648 12.1416Z"
                                    fill="black"></path>
                            </svg>
                        </label>
                        <input id="image_file" type="file" name="image" class="d-none">
                    </div> --}}
                </div>
            </div>
            <div class="col-lg-12 ps-3">
                <h6 class="m-0 text-center">{{ $user->name }}</h6>
                <p class="text-center"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                @if ($user->phone != null)
                    <p class="text-center"><a href="tel:+{{ $user->phone }}">{{ $user->phone }}</a></p>
                @endif

                @if ($user->user_code != null)
                    <p class="text-center fw-bold">({{ $user->user_code }})</p>
                @endif

                @if (Auth::user()->seller || Auth::user()?->shop)
                    @if(Auth::user()->shop?->rejected == '1')
                        <p class="text-center">{{__('Seller status : Rejected')}}</p>
                    @elseif(!Auth::user()->shop?->verification_info)
                        <p class="text-center">{{__('Seller status : Please Apply')}}</p>
                    @elseif (Auth::user()->shop?->verification_status)
                        <p class="text-center">{{__('Seller status : Confirmed')}}
                            <a href="{{ route('seller.dashboard') }}" class="theme-btn-two">{{__('Seller Dashboard')}} </a>
                        </p>
                    @else
                        <p class="text-center">{{__('Seller status : Pending')}}</p>
                    @endif
                @endif
            </div>
            <div class="col-12 pt-2 mb-4">
                <div class="d-flex justify-content-center"></div>
            </div>
        </div>
        <ul class="list-unstyled mb-0 theme-link">
            <li class="border-bottom mb-0"><a
                    class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['dashboard']) }}"
                    href="{{ route('dashboard') }}"><i class="fa fa-user me-2"></i>Manage Profile </a></li>

            <li class="border-bottom accordion accordion-flush mb-0 " id="accordionFlushExample"
                data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false"
                aria-controls="flush-collapseOne">
                <a href="javascript:void(0);"
                    class="nav-link-style d-flex gap-2 align-items-center p-3 dropdown-tongle">
                    <svg id="Group_8142" data-name="Group 8142" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16" viewBox="0 0 16 16">
                        <defs>
                            <clipPath id="clip-path">
                                <rect id="Rectangle_1420" data-name="Rectangle 1420" width="16" height="16"
                                    fill="#b5b5bf"></rect>
                            </clipPath>
                        </defs>
                        <g id="Group_8141" data-name="Group 8141" clip-path="url(#clip-path)">
                            <path id="Path_3023" data-name="Path 3023"
                                d="M5.3,13.642,11.217,5.2,9.58,4.059a.5.5,0,0,1-.819-.573L11.055.213a.5.5,0,0,1,.819.573L17.607,4.8a.5.5,0,0,1,.819.573L16.131,8.643a.5.5,0,0,1-.819-.573L13.675,6.924,7.762,15.361A1.5,1.5,0,0,1,5.3,13.642M15.886,7.251l1.147-1.637L11.3,1.6,10.153,3.241ZM6.246,14.91a.5.5,0,0,0,.7-.122l5.913-8.437-.819-.573L6.123,14.215a.5.5,0,0,0,.123.7"
                                transform="translate(-5.033 0)" fill="#b5b5bf"></path>
                            <path id="Path_3024" data-name="Path 3024"
                                d="M3,30.472a.5.5,0,0,0,.5.5h7a.5.5,0,1,0,0-1h-7a.5.5,0,0,0-.5.5"
                                transform="translate(3.5 -14.986)" fill="#b5b5bf"></path>
                            <path id="Path_3025" data-name="Path 3025"
                                d="M6.5,24.976h4a.5.5,0,0,1,.5.5v2H10v-1.5H7v1.5H6v-2a.5.5,0,0,1,.5-.5"
                                transform="translate(2 -12.488)" fill="#b5b5bf"></path>
                            <path id="Path_3026" data-name="Path 3026"
                                d="M0,24.478H0a.5.5,0,0,0,.5.5h1a.5.5,0,1,0,0-1H.5a.5.5,0,0,0-.5.5"
                                transform="translate(14 -11.989)" fill="#b5b5bf"></path>
                            <path id="Path_3027" data-name="Path 3027"
                                d="M4.439,19.007a.5.5,0,0,0-.707,0l-.707.706a.5.5,0,0,0,.707.706l.707-.706a.5.5,0,0,0,0-.706"
                                transform="translate(9.975 -9.431)" fill="#b5b5bf"></path>
                        </g>
                    </svg>
                    Auction
                </a>
            </li>
            <li class="border-bottom-0 mb-0">
                <ul id="flush-collapseOne"
                    class="accordion-collapse {{ activeRoute(['bidded_products', 'auction_purchase_history']) ? '' : 'collapse' }} bg-active"
                    aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <li class="border-bottom mb-0">
                        <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['bidded_products']) }}"
                            href="{{ route('bidded_products') }}">
                            <i class="fas fa-info-circle me-2"></i>
                            Bidded Products
                        </a>
                    </li>
                    <li class="border-bottom mb-0">
                        <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['auction_purchase_history']) }}"
                            href="{{ route('auction_purchase_history') }}">
                            <i class="fas fa-info-circle me-2"></i>
                            Purchase History
                        </a>
                    </li>
                </ul>
            </li>

            <li class="border-bottom-0 mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 border-bottom {{ activeRoute(['wishlists.auction']) }}"
                    href="{{ route('wishlists.auction') }}">
                    <i class="fas fa-heart me-2"></i>Watch Items
                    @if (Auth::check() && count(Auth::user()->wishlists) > 0)
                        <div class="ms-auto badge-pill badge bg-secondary">{{ count(Auth::user()->wishlists) }}</div>
                    @endif
                </a>
            </li>

            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['compare']) }}"
                    href="{{ route('compare') }}">
                    <i class="fa-sharp fa-solid fa-code-compare me-2"></i>Compare
                    @if (Session::has('compare'))
                        <div class="ms-auto badge-pill badge bg-secondary">{{ count(Session::get('compare')) }}</div>
                    @endif
                </a>
            </li>
            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['customer.addresses']) }}"
                    href="{{ route('customer.addresses') }}">
                    <i class="fas fa-map me-2"></i>Addresses
                    <div class="ms-auto badge-pill badge bg-secondary">{{ count(Auth::user()->addresses) }}</div>
                </a>
            </li>

            @if(auth()->user()->shop && auth()->user()->shop?->verification_info == null)
            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['apply-verification']) }}"
                    href="{{ route('apply-verification') }}">
                    <i class="fas fa-edit  me-2"></i>{{ __('Apply Verification')  }}
                </a>
            </li>
            @endif

            @if(auth()->user()->shop?->verification_status)
            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['bank-info']) }}"
                    href="{{ route('bank-info') }}">
                    <i class="fas fa-bank me-2"></i>{{ __('Bank Information') }}
                </a>
            </li>
            @endif


            @if(Auth::user()->user_type != 'seller')
            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['shops.create']) }}"
                    href="{{ route('shops.create') }}">
                    <i class="fas fa-user me-2"></i>
                    @if (Auth::user()->shop)
                        {{ __('Seller Status') }}
                    @else
                        {{ __('Become Seller') }}
                    @endif
                </a>
            </li>
            @endif
            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['purchase_history.index']) }}"
                    href="{{ route('purchase_history.index') }}">
                    <i class="fa fa-bag-shopping me-2"></i> Orders

                </a>
            </li>

            <li class="border-bottom mb-0">
                <a class="nav-link-style d-flex align-items-center p-3 {{ activeRoute(['user.profile.change-password']) }}"
                    href="{{ route('user.profile.change-password') }}">
                    <i class="fa fa-key  me-2"></i> Change Password
                </a>
            </li>

            <li class="border-bottom-0 mb-0"><a href="{{ route('logout') }}"
                    class="nav-link-style d-flex align-items-center p-3" role="button"><i
                        class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </div>
</div>
