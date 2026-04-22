@extends('frontend.layouts.xt-app')

@section('content')
    <style>
        .action_switcher {
            margin-left: 13px;
        }

        .action_switcher .theme-btn-one {
            overflow: hidden;
            padding: 7px 16px;
            border-width: 1px;
        }

        .action_switcher .theme-btn-one:first-child {
            border-radius: 25px 0 0 25px;
            border-right: 0;
        }

        .action_switcher .theme-btn-one:last-child {
            border-radius: 0 25px 25px 0;
        }

        .action_switcher .theme-btn-one::after {
            border-radius: 0;
        }

        .theme-btn-one.active:after {
            -webkit-transform: scaleY(1);
            transform: scaleY(1);
            -webkit-transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
            transition-timing-function: cubic-bezier(0.52, 1.64, 0.37, 0.66);
        }

        .theme-btn-one.active {
            color: #fff;
        }
    </style>
    <!-- shop-section -->
    <section class="shop-section items-container  clearfix aos-init aos-animate" data-aos="fade-up">
        <div class="auto-container wow fadeInUp animated animated animated">
            <div class="sec-title">
                <h2>{{ translate('All Auctions') }}</h2>
                <span class="separator"
                    style="background-image: url('{{ static_asset('xt-assets/images/icons/separator-1.png') }}');"></span>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="d-flex align-items-center justify-content-end mb-2  action_switcher">
                        <button id="collectionBtn" class="theme-btn-one {{ activeRoute(['auction_collection']) }}"
                            onclick="toggleButton('collectionBtn', 'auction_collection')">Collection</button>
                        <button id="itemsBtn" class="theme-btn-one {{ activeRoute(['auction_products.all','auction.products.category']) }}"
                            onclick="toggleButton('itemsBtn', 'auction_products.all')">Items</button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="shop__all__top__bar">
                        <div class="shop__all__top__result__count" id="recoard_details">
                            Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }}
                            results

                        </div>
                        <div class="shop__all__top__filter__btn">
                            <a class="btn btn-default" data-bs-toggle="offcanvas" href="#shopFilterbar" role="button">
                                <img src="{{ static_asset('xt-assets/images/icons/filter-icon.svg') }}" alt="">
                                Filter
                            </a>
                        </div>
                        <div class="shop__all__top__left">
                            <div class="hero__search__bar">
                                <input type="text" name="searchkeyword" value="{{ isset($keywords) ? $keywords : '' }}"
                                    placeholder="Search..." onkeypress="if(event.key === 'Enter'){ searchKeywords() }"
                                    class="hero__search__input" onen="searchKeywords()">
                                <button onclick="searchKeywords()" class="hero__search__btn">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18.031 16.617L22.314 20.899L20.899 22.314L16.617 18.031C15.0237 19.3082 13.042 20.0029 11 20C6.032 20 2 15.968 2 11C2 6.032 6.032 2 11 2C15.968 2 20 6.032 20 11C20.0029 13.042 19.3082 15.0237 18.031 16.617ZM16.025 15.875C17.2941 14.5699 18.0029 12.8204 18 11C18 7.133 14.867 4 11 4C7.133 4 4 7.133 4 11C4 14.867 7.133 18 11 18C12.8204 18.0029 14.5699 17.2941 15.875 16.025L16.025 15.875Z"
                                            fill="black"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="shop__all__top__right d-flex">
                            <select class="form-control form-select" name="sort" onchange="sort(this.value)">
                                <option value="">Sort By</option>
                                {{-- <option value="newest" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'newest')>Newest</option>
                                <option value="oldest" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'oldest')>Oldest</option> --}}
                                <option value="soonest-end"  title="This will sort by soonest ending auctions" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'soonest-end')>Sooner end</option>
                                <option value="latter-end" title="This will sort by latest  ending auctions" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'latter-end')>Last end</option>
                                <option value="soonest-start" title="This will sort by soonest starting upcoming auctions" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'soonest-end')>Sooner Start</option>
                                {{-- <option value="future-end" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'future-end')>Future end</option>  --}}
                                <option value="price-asc" title="Sort by price from low to high" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'price-asc')>Price low to high</option>
                                <option value="price-desc" title="Sort by price from high to low" @selected(old('sort_by', isset($sort_by) ? $sort_by : '') == 'price-desc')>Price high to low</option>
                            </select>
                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                @if ($products->count() == 0)
                    <div class="col-6 mx-auto text-center">
                        <div class="bg-dark mt-5 p-3 rounded" role="alert">
                            No Data found
                        </div>
                    </div>
                @endif

                @foreach ($products as $key => $product)
                    {{-- @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product]) --}}

                    {{-- @dump($product->toArray()) --}}

                    @if (isset($product->total))
                        @include(
                            'frontend.' . get_setting('homepage_select') . '.partials.product_box_xt_collection',
                            ['product' => $product->lots]
                        )
                    @else
                        @include(
                            'frontend.' . get_setting('homepage_select') . '.partials.product_box_xt',
                            ['product' => $product]
                        )
                    @endif
                @endforeach
            </div>
            <div class="aiz-pagination aiz-pagination-center mt-4">
                {{-- $products->appends(request()->input())->links('pagination::bootstrap-5') --}}
                {{ $products->appends(request()->input())->links('frontend.xthome.partials.custom_pagination') }}
            </div>
        </div>
    </section>
    <!-- instagram-section end -->

    @include('auction.frontend.xthome.filterOffCanvas.filter_offcanvas')

    <script>
        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function bid_modal() {
            @if (Auth::check() && (Auth::user()->user_type == 'customer' || Auth::user()->user_type == 'seller'))
                $('#bid_for_product').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function toggleButton(buttonId, route) {
            const collectionBtn = document.getElementById('collectionBtn');
            const itemsBtn = document.getElementById('itemsBtn');

            collectionBtn.classList.remove('active');
            itemsBtn.classList.remove('active');

            document.getElementById(buttonId).classList.add('active');

            @php
                $getQuery = request()->query();
                $urlSlugQuery = isset($category_ids) ? ['search' => 'filter', 'category_ids' => $category_ids] : [];
                $mergedQuery = array_merge($getQuery, $urlSlugQuery);
                $collectionNavigate = route('auction_collection', $mergedQuery);
                $itemNavigate = route('auction_products.all', $mergedQuery);
            @endphp

            var collectionNavigate = "{!! $collectionNavigate !!}";
            var itemNavigate = "{!! $itemNavigate !!}";
            window.location.href = route === "auction_collection" ? collectionNavigate : itemNavigate
        }


        @include('auction.frontend.xthome.filterOffCanvas.filter_offcanvas_script')
    </script>
@endsection
