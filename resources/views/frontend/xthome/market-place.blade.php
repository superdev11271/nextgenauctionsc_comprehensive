@extends('frontend.layouts.xt-app')

@section('content')

        <!-- shop-section -->
        <section class="shop-section items-container  clearfix aos-init aos-animate" data-aos="fade-up">
            <div class="auto-container wow fadeInUp animated animated animated">
               <div class="sec-title">
                  <h2>Listing Products</h2>
                  {{-- <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, quas.</p> --}}
                  <span class="separator" style="background-image: url({{ static_asset('xt-assets')}}/images/icons/separator-1.png);"></span>
               </div>

                <div class="shop__all__top__bar">
                    <div class="shop__all__top__result__count" id="recoard_details">
                        Showing {{ $products->firstItem() }} - {{ $products->lastItem() }} of {{ $products->total() }} results
                    </div>
                    <div class="shop__all__top__filter__btn">
                        <a class="btn btn-default" data-bs-toggle="offcanvas" href="#shopFilterbar" role="button">
                                <img src="{{static_asset('xt-assets')}}/images/icons/filter-icon.svg" alt="">
                                Filter
                        </a>
                    </div>
                    <div class="shop__all__top__left">
                        <div class="hero__search__bar">
                            <input type="text" name="searchkeyword" value="{{isset($keywords)?$keywords:''}}" placeholder="Search..."
                            onkeypress="if(event.key === 'Enter'){ searchKeywords() }"
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
                    <div class="shop__all__top__right">
                        <select class="form-control form-select" name="sort" onchange="sort(this.value)">
                            <option value="">Sort By</option>
                            <option value="newest" @selected(old("sort_by", isset($sort_by)?$sort_by:"") == "newest")>Newest</option>
                            <option value="oldest" @selected(old("sort_by", isset($sort_by)?$sort_by:"") == "oldest")>Oldest</option>
                            <option value="price-asc" @selected(old("sort_by", isset($sort_by)?$sort_by:"") == "price-asc")>Price low to high</option>
                            <option value="price-desc" @selected(old("sort_by", isset($sort_by)?$sort_by:"") == "price-desc")>Price high to low</option>
                        </select>
                    </div>
                </div>

               <div class="row">
                @if ($products->count()==0)
                <div class="col-12 text-center">
                    No Data found
                </div>
                @endif

                  @foreach($products as $key => $product)
                     @include('frontend.'.get_setting('homepage_select').'.partials.product_box_xt',['product' => $product])
                  @endforeach
               </div>
               <div class="aiz-pagination aiz-pagination-center mt-4">
                  {{-- $products->appends(request()->input())->links('pagination::bootstrap-5') --}}
                  {{  $products->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
               </div>
            </div>
         </section>
        <!-- instagram-section end -->

        {{-- @include('frontend.xthome.partials.filters') --}}
        @include("auction.frontend.xthome.filterOffCanvas.filter_offcanvas")


        <script>
            @include("auction.frontend.xthome.filterOffCanvas.filter_offcanvas_script")
        </script>
@endsection
