<div class="row">
    <div class="col-12">
        @if ($detailedProduct->est_shipping_days)
            <div class="info">
                <p><i class="fa fa-shipping-fast"></i>
                    <span> {{ translate('Estimate Shipping Time') }} : {{ $detailedProduct->est_shipping_days }}
                        {{ translate('Days') }}</span>
                </p>
            </div>
        @endif
    </div>
</div>

@if ($detailedProduct->auction_product != 1)
        <div class="row">
            <div class="col-12">
                @php
                    $total = 0;
                    $total += $detailedProduct->reviews->count();
                @endphp
                <span class="rating rating-mr-1">
                    {{ renderStarRating($detailedProduct->rating) }}
                </span>
                <span class="ml-1 opacity-70 fs-14">({{ $total }}
                    {{ translate('reviews') }})</span>
            </div>
        </div>
@endif

@if(get_setting('marketplace_product_expiry'))
    @if($detailedProduct->auction_end_date)
    <div class="row no-gutters">
        <div class="col-12">
            <span style="font-weight: bold; font-size: 15px; color: goldenrod;">
                {{ translate('Time Left') }}
            </span>
            <span class="countdown-timer" 
                data-id="{{ $detailedProduct->id }}"
                data-end="{{ \Carbon\Carbon::parse($detailedProduct->auction_end_date)->timezone(config('app.timezone'))->format('Y/m/d H:i:s') }}">
                <span id="countdown-display-{{ $detailedProduct->id }}" 
                      style="font-weight: bold; font-size: 20px; color: goldenrod;">
                </span>
            </span>       
        </div>
    </div>
    @endif  
@endif

<!-- Brand Logo & Name -->
<div class="row no-gutters">
    <div class="col-12">{{ translate('Brand') }} :
        @if ($detailedProduct->brand != null)
            <a href="{{ route('products.brand', $detailedProduct->brand->slug) }}">
                {{ $detailedProduct->brand->name }}
            </a>
        @endif</div>
</div>
@php $category = get_category([$detailedProduct->category_id]) @endphp
    @if(isset($category[0]))
    <div class="row no-gutters">
        <div class="col-12">{{ translate('Category') }} :
            @if ($detailedProduct->auction_product != 1)
            <a href="{{ route('products.category', $category[0]->slug) }}">
                {{ $category[0]->name }}
            </a>
            @else
            <a href="{{ route('auction.products.category', $category[0]->slug) }}">
                {{ $category[0]->name }}
            </a>
            @endif
        </div>
    </div>
@endif

<div class="row no-gutters">
    @if (($detailedProduct->added_by == 'seller' || $detailedProduct->user->shop) && get_setting('vendor_system_activation') == 1)
        <div class="col-12">{{ translate('Sold by') }} : {{ $detailedProduct->user->shop?->name ?? 'No Found' }}</div>
    @else
        <div class="col-12">{{ translate('Sold by') }} : {{ translate('Admin Product') }}</div>
    @endif

</div>
@if($detailedProduct->pdf)
<div class="row no-gutters">
    <div class="col-3 col-lg-3">{{ translate('Attachment') }} :  </div>
    <div class="col col-lg-9">
            <a href="{{uploaded_asset($detailedProduct->pdf)}}">View</a>
    </div>
</div>
@endif
<div class="row no-gutters">
    <div class="col-3 col-lg-3">{{ translate('Delivery Method:') }}</div>
    <div class="col col-lg-9">
            <span>{{getDeliveryType($detailedProduct->id, true)}}</span>
    </div>
</div>
    @if($detailedProduct->auction_product == 0 && $detailedProduct->user->phone)
    <div class="row no-gutters">
        <div class="col-3 col-lg-3">{{ translate('Seller Contact:') }}</div>
        <div class="col col-lg-9">
            <span>{{$detailedProduct->user->phone}}</span>
        </div>
    </div>
    @endif
    @if($detailedProduct->auction_product == 0 && $detailedProduct->user->email)
    <div class="row no-gutters">
        <div class="col-3 col-lg-3">{{ translate('Seller Mail:') }}</div>
        <div class="col col-lg-9">
            <span>{{$detailedProduct->user->email}}</span>
        </div>
    </div>
    @endif
<hr>
<div class="row">
    <div class="col-md-12">
        <ul class="column_list">
            {{-- Excluding Images and Text Area --}}
            @foreach ($detailedProduct->attrs as $attribute)
                @if (!in_array($attribute->type(), [0, 2]))
                    <li><span>{{ $attribute->attribute_name }}:</span>
                        {{ $attribute->value }}
                    </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

<form id="option-choice-form">
    @csrf
    <div class="row no-gutters pb-3 " id="chosen_price_div">
        <div class="col-3 col-lg-2">
            <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Price') }}</div>
        </div>
        <div class="col col-lg-10">
            <div class="product-price">
                @php $stock = $detailedProduct->stocks->first(); @endphp
                 @if ($stock && $stock->quantity > 0)
                <strong  class="fs-20 fw-700 theme-one">
                        <span id="chosen_price">
                            @if (home_base_price($detailedProduct) != home_discounted_base_price($detailedProduct))
                                <del class="fw-400 text-secondary me-2">{{ home_base_price($detailedProduct) }}</del>
                            @endif

                            {{ home_discounted_base_price($detailedProduct) }}
                        </span>


                </strong>
               @else
                <strong  class="fs-20 fw-700 theme-one">

                    <span id="chosen_price">
                        @if (home_discounted_base_price_by_stock_id($stock->id) != home_base_price_by_stock_id($stock->id))
                            <del class="fw-400 text-secondary me-2"> {{home_base_price_by_stock_id($stock->id) }} </del>
                        @endif
                        {{home_discounted_base_price_by_stock_id($stock->id)}}
                    </span>
                </strong>

                @endif
                {{-- @if ($detailedProduct->unit != null)
                    <span class="opacity-70 ml-1">/{{ $detailedProduct->getTranslation('unit') }}</span>
                @endif --}}
            </div>
        </div>
    </div>
    @if ($detailedProduct->unit != null)
    <div class="row no-gutters pb-3 ">
        <div class="col-3 col-lg-2">
            <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Unit') }}</div>
        </div>
        <div class="col col-lg-10">
            <div class="product-price">
                <strong class="fs-20 fw-700 theme-one">
                    
                        <span id="chosen_price">{{ $detailedProduct->productUnit?->name }}</span>
                    
                </strong>
            </div>
        </div>
    </div>
    @endif
    <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
    @if ($detailedProduct->digital == 0)
        <!-- Choice Options -->
        @if ($detailedProduct->choice_options != null)
            @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                <div class="row no-gutters mb-3">
                    <div class="col-3 col-lg-2">
                        <div class="text-secondary fs-14 fw-400 mt-2 ">
                            {{ get_single_attribute_name($choice->attribute_id) }}
                        </div>
                    </div>
                    <div class="col col-lg-10">
                        <div class="aiz-radio-inline">
                            @foreach ($choice->values as $key => $value)
                                <label class="aiz-megabox pl-0 mr-2 mb-0">
                                    <input type="radio" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $value }}" @if ($key == 0) checked @endif />
                                    <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center size-36px bg_small">{{ $value }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        <!-- Color Options -->
        @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
            <div class="row no-gutters mb-3">
                <div class="col-3 col-lg-2">
                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color') }}:</div>
                </div>
                <div class="col col-lg-10">
                    <div class="aiz-radio-inline">
                        @foreach (json_decode($detailedProduct->colors) as $key => $color)
                            <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip"
                                data-title="{{ get_single_color_name($color) }}">
                                <input type="radio" name="color" value="{{ get_single_color_name($color) }}"
                                    @if ($key == 0) checked @endif>
                                <span class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                    <span class="size-25px d-inline-block rounded-1" style="background: {{ $color }};"></span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Quantity + Add to cart -->
        <div class="row no-gutters mb-3">
            <div class="col-3 col-lg-2">
                <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Quantity') }}:</div>
            </div>
            <div class="col col-lg-10">
                <div class="product-quantity d-flex gap-2 align-items-center">
                    @php
                        $qty = 0;
                        foreach ($detailedProduct->stocks as $key => $stock) {
                            $qty += $stock->qty;
                        }
                    @endphp
                    <div class="cart-qty d-flex justify-content-between flex aiz-plus-minus">
                        <button type="button" class="qty-btn cart-qty-dec c" data-type="minus" data-field="quantity"
                            disabled="disabled">-</button>
                        <input type="number" name="quantity" class="cart-qty-input form-control input-number"
                            placeholder="1" value="{{ $qty !=0 ? $detailedProduct->min_qty : '0' }}"
                            min="{{ $detailedProduct->min_qty }}" max="{{$qty}}" readonly="">
                        <button type="button" class="qty-btn cart-qty-inc c" data-type="plus" data-field="quantity">+</button>
                    </div>
                    <div class="avialable-amount opacity-60">
                        @if ($detailedProduct->stock_visibility_state == 'quantity')
                            @if($qty > 0)
                            (<span id="available-quantity">{{$detailedProduct->stocks[0]->qty}}</span>
                            {{ translate('available') }})
                            @endif
                        @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                            (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                        @endif

                        <button type="button" class="btn btn-secondary {{ $qty == 0 ? '' : 'd-none'  }} out-of-stock fw-600   mb-0" disabled>
                                <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                        </button>

                    </div>

                </div>
            </div>
        </div>
    @else
        <!-- Quantity -->
        <input type="hidden" name="quantity" value="1">
    @endif

    <!-- Total Price -->


</form>



<!-- Add to cart & Buy now Buttons -->
<div class="mt-3">
    @if ($detailedProduct->digital == 0)
        @if ($detailedProduct->external_link != null)
            <a class="theme-btn-one buy-now fw-600 mb-3" href="{{ $detailedProduct->external_link }}">
                <i class="la la-share"></i>{{ translate($detailedProduct->external_link_btn) }}
            </a>
        @else
            @if($detailedProduct->user->id != auth()->user()?->id)
            <a href="javascript:void(0)"   class="theme-btn-one w-100 d-flex justify-content-center mb-3 add-to-cart {{ $qty == 0 ? 'd-none' : ''  }}"
                @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                <i class="lnr lnr-cart"></i><span>{{ translate('Add to cart') }}</span>
            </a>

            <a href="javascript:void(0)"  class="theme-btn-two w-100  mb-3 buy-now {{ $qty == 0 ? 'd-none' : ''  }}"
                @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif><i
                    class="lnr lnr-heart"></i>
                <span>{{ translate('Buy Now') }}</span>
            </a>
            @else
            <div class="rounded bg-dark text-center p-3" >
                <div class="fs-18 fw-600">
                {{__("User Can't Buy Their Own Products")}}
                </div>
            </div>
            @endif
        @endif

    @elseif ($detailedProduct->digital == 1)
        @if($detailedProduct->user->id != auth()->user()?->id)
        <a href="javascript:void(0)" class="theme-btn-one w-100 d-flex justify-content-center  mb-3 add-to-cart {{ $qty == 0 ? 'd-none' : ''  }}"
            @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
            <i class="lnr lnr-cart"></i><span> {{ translate('Add to cart') }}</span>
        </a>

        <a href="{{ route('cart') }}" class="theme-btn-two w-100  mb-3 buy-now {{ $qty == 0 ? 'd-none' : ''  }}"
            @if (Auth::check()) onclick="addToCart()" @else onclick="showLoginModal()" @endif><i
                class="lnr lnr-heart"></i>
            <span>{{ translate('Buy Now') }}</span>
        </a>
        @else
        <div class="alert alert-warning" role="alert">
            {{__("User Can't Buy Their Own Products")}}
        </div>
        @endif

    @endif
</div>

<!-- Promote Link -->
<!-- <div class="d-table width-100 mt-3">
    <div class="d-table-cell">
        @if (Auth::check() &&
                addon_is_activated('affiliate_system') &&
                get_affliate_option_status() &&
                Auth::user()->affiliate_user != null &&
                Auth::user()->affiliate_user->status)
            @php
                if (Auth::check()) {
                    if (Auth::user()->referral_code == null) {
                        Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                        Auth::user()->save();
                    }
                    $referral_code = Auth::user()->referral_code;
                    $referral_code_url =
                        URL::to('/product') . '/' . $detailedProduct->slug . "?product_referral_code=$referral_code";
                }
            @endphp
            <div>
                <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded-0  mb-3"
                    data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                    data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
            </div>
        @endif
    </div>
</div> -->

<!-- Refund -->
@php
    $refund_sticker = get_setting('refund_sticker');
@endphp
@if (addon_is_activated('refund_request'))
    <div class="row no-gutters mt-3">
        <div class="col-3 col-lg-2">
            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Refund') }}</div>
        </div>
        <div class="col col-lg-10">
            @if ($detailedProduct->refundable == 1)
                <a href="{{ route('returnpolicy') }}" target="_blank">
                    @if ($refund_sticker != null)
                        <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                    @else
                        <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                    @endif
                </a>
                <a href="{{ route('returnpolicy') }}" class="text-blue hov-text-primary fs-14 ml-3"
                    target="_blank">{{ translate('View Policy') }}</a>
            @else
                <div class="text-dark fs-14 fw-400 mt-2">{{ translate('Not Applicable') }}</div>
            @endif
        </div>
    </div>
@endif

<!-- Seller Guarantees -->
@if ($detailedProduct->digital == 1)
    @if ($detailedProduct->added_by == 'seller')
        <div class="row no-gutters mt-3">
            <div class="col-3 col-lg-2">
                <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
            </div>
            <div class="col col-lg-10">
                @if ($detailedProduct->user->shop?->verification_status == 1)
                    <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                @else
                    <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                @endif
            </div>
        </div>
    @endif
@endif

<script>
    function showLoginModal() {
        $('#login_modal').modal('show');
    }
</script>
