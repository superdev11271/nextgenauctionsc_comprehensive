@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush
@section('content')


<section class="shop-section account-details pt-5">
    <div class="auto-container">
       <div class="row">
          <div class="col-lg-12 col-xxl-12">
                <div class="card mb-5">
                   <div class="card-header py-3 d-flex flex-wrap gap-4 justify-content-between">
                    <h5 class="m-0">{{ translate('Compare Products')}}</h5>
                    @if(Session::has('compare'))
                    <div>
                        <a href="{{ route('compare.reset') }}" class="theme-btn-one py-2">{{ translate('Reset Compare List')}}</a>
                    </div>
                    @endif
                 </div>


                   <div class="card-body compare-page">
                        <div class="row gutters-16 mb-4" id="showCompareMsg">
                            @if(Session::has('compare'))
                            @if(count(Session::get('compare')) > 0)
                            @foreach (Session::get('compare') as $key => $item)
                            @php
                                $product = get_single_product($item);
                            @endphp
                            <div class="col-xl-3 col-lg-4 col-md-6 py-3" id="compare_{{ get_single_product($item)->id }}">
                                <div class="shop-block-one c">
                                    <button type="button" class="btn-close btn-remove"  onclick="removeComparelist({{ get_single_product($item)->id }})"  data-bs-dismiss="card-compare" aria-label="Close"></button>
                                    <div class="inner-box" aria-modal="true" role="dialog">
                                        <div class="pb-2 border p-2">
                                            <p class="mb-0 h-45px body-color text-truncate-2 mt-1">
                                                <a class="fs-14 body-color" href="{{ route('product', get_single_product($item)->slug) }}" title="{{ get_single_product($item)->getTranslation('name') }}"> {{ Str::limit(get_single_product($item)->getTranslation('name'),20) }}</a>
                                            </p>
                                        </div>
                                        <figure class="image-box">
                                            @if($product->variant_product)
                                                @if( get_setting('marketplace_product_expiry') && $product->auction_end_date)
                                                    <span class="runing text-dark">
                                                        Expire
                                                    <span class="countdown-timer" 
                                                        data-id="{{ $product->id }}"
                                                        data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                                                        <span id="countdown-display-{{ $product->id }}"></span>
                                                    </span>
                                                </span>
                                                @endif
                                            <img src="{{ uploaded_asset($product->stocks->first()->image, 'thumbnail') }}" alt="{{ translate('Product Image') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                                            @else
                                                <img src="{{ uploaded_asset(get_single_product($item)->thumbnail_img) }}" alt="{{ translate('Product Image') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                                            @endif
                                            <ul class="info-list clearfix">
                                                <li><a href="javascript:void(0)" onclick="addToWishList({{ get_single_product($item)->id }})"><i id="watchitem{{get_single_product($item)->id }}"class="fa-regular fa-heart watchitem{{get_single_product($item)->id }} {{ isWishlisted(get_single_product($item)->id) ? 'fa-solid' : '' }}"></i></a></li>
                                                <li><a href="{{ route('product', get_single_product($item)->slug) }}">{{ translate('Add to Cart') }}</a></li>
                                                <li>
                                                    <a href="javascript:void(0)"  onclick="addToCompare({{ get_single_product($item)->id }})"
                                                    data-toggle="tooltip" data-title="{{ translate('Add to compare') }}" data-placement="left">
                                                    <i id="compare-{{ get_single_product($item)->id }}" class="fa-sharp fa-solid fa-code-compare  compare-{{ get_single_product($item)->id }} {{ isCompare(get_single_product($item)->id) ? 'text-danger' : '' }}"></i>
                                                </a>
                                            </li>
                                            </ul>
                                        </figure>
                                        <div class="lower-content auctionCardBottomBox">
                                            @if($product->variant_product)
                                            <div class="d-flex gap-2">
                                                <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price')}}</div>
                                                <div class="mb-0 fs-14  body-color  mb-2">
                                                    @if(home_base_price_by_stock_id($product->stocks->first()->id) != home_discounted_base_price_by_stock_id($product->stocks->first()->id))
                                                    <del class="fw-400 opacity-50 mr-1">{{home_base_price_by_stock_id($product->stocks->first()->id) }}</del>
                                                @endif
                                                <span class="fw-700">{{ home_discounted_base_price_by_stock_id($product->stocks->first()->id)}} </span>
                                                </div>
                                            </div>
                                            @else
                                            <div class="d-flex gap-2">
                                                <div class="fs-16 head-color mb-0 fw-700">{{ translate('Price')}}</div>
                                                <div class="mb-0 fs-14  body-color  mb-2">
                                                    @if(home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fw-400 opacity-50 mr-1">{{ home_base_price($product) }}</del>
                                                @endif
                                                <span class="fw-700">{{ home_discounted_base_price($product) }}</span>
                                                </div>
                                            </div>
                                            @endif
                                            <div class="d-flex gap-2">
                                                <div class="fs-16 head-color mb-0 fw-700">{{ translate('Category')}}</div>
                                                <div class="mb-0 fs-14 body-color text-wrap  mb-2">
                                                    @if (get_single_product($item)->main_category != null)
                                                        {{ get_single_product($item)->main_category->getTranslation('name') }}
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <p class="fs-16 head-color mb-0 fw-700">{{ translate('Brand')}}</p>
                                                <p class="mb-0 fs-14 body-color  mb-2">
                                                    @if (get_single_product($item)->brand != null)
                                                    {{ get_single_product($item)->brand->getTranslation('name') }}
                                                   @endif
                                                </p>
                                            </div>
                                            
                                            @if (get_setting('marketplace_product_expiry') && $product->auction_end_date)
                                                <div class="d-flex gap-2">
                                                    <div class="fs-16 head-color mb-0 fw-700">{{ translate('Time Left : ') }}</div>
                                                    <div class="mb-0 fs-14 body-color  mb-2 text-wrap">
                                                        <div class="d-flex gap-2 pb-2" style="font-weight: bold; font-size: 15px; color: goldenrod;">
                                                            <span class="countdown-timer" 
                                                                data-id="{{ $product->id }}"
                                                                data-end="{{ \Carbon\Carbon::parse($product->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                                                                <span id="countdown-display-{{ $product->id }}"></span>
                                                                </span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                                                {{-- <h6 class="pb-2 product_name">{{ $product->getTranslation('name') }}</h6>
                                            <div class="d-flex flex-wrap items-items-center">
                                                @if (home_base_price($product) != home_discounted_base_price($product))
                                                    <del class="fw-400 text-secondary me-2">{{ home_base_price($product) }}</del>
                                                @endif
                                                <!-- price -->
                                                <span class="price fw-bold">{{ home_discounted_base_price($product) }}</span>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                            @else
                            <div class="rounded bg-dark text-center p-3">
                                <div class="fs-18 fw-600">
                                {{ translate('Your comparison list is empty')}}
                                </div>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
          </div>
       </div>
    </div>
 </section>
 <script type="text/javascript">
    function removeComparelist(productId) {
        var baseUrl = '{{ url('/') }}';

    // Send AJAX request to remove the product
    $.ajax({
        url: baseUrl + '/compare/remove/' + productId,
        type: 'POST',
        data: {_token: '{{ csrf_token() }}'},
        success: function(response) {

          $("#compare_"+productId).hide();
          AIZ.plugins.notify('success', "{{ translate('Item has been removed from compare') }}");
            $('#compare_items_sidenav').html(parseInt($('#compare_items_sidenav').html())-1);
        },
        error: function(xhr) {
            AIZ.plugins.notify('warning', "{{ translate('Something went wrong') }}");
            // Handle errors if any
        }
    });
}


    </script>
@endsection
