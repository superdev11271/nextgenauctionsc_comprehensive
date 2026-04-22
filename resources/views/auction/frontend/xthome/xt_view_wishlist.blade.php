@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/account-details.css" rel="stylesheet">
@endpush
@section('content')
    

    <section class="shop-section account-details pt-5">
        <div class="auto-container">
            <div class="row">
                @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">
                    @if (count($wishlists) > 0)Orders
                        <div class="auto-container parentsCart  table-responsive">
                            <table class="shopping-cart table table-responsive-md text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="image-product">Product Name</th>
                                        <th class="w-20">Price</th>
                                        <th class="w-60">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($wishlists as $key => $wishlist)
                                        <tr class="cart-item" id="wishlist_{{ $wishlist->id }}">
                                            <td><a href="{{ route('product', $wishlist->product->slug) }}"><img
                                                        src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}"
                                                        class="lazyload mx-auto img-fit"
                                                        title="{{ $wishlist->product->getTranslation('name') }}"
                                                        class="img-fluid-product border"
                                                        alt="{{ $wishlist->product->getTranslation('name') }}">{{ $wishlist->product->getTranslation('name') }}</a>
                                            </td>
                                            <td>{{ home_discounted_base_price($wishlist->product) }}@if (home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))
                                                    <del
                                                        class="opacity-60 ml-1">{{ home_base_price($wishlist->product) }}</del>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <div class="d-flex justify-content-end gap-2"><a href="javascript:void(0)"
                                                        onclick="removeFromWishlist({{ $wishlist->id }})"
                                                        class="theme-btn-two"><i class="fa fa-trash-alt"
                                                            aria-hidden="true"></i> <span>Delete</span></a>
                                                    <a href="{{ route('product', $wishlist->product->slug) }}"
                                                        class="theme-btn-one"><i class="fa fa-shopping-cart"
                                                            aria-hidden="true"></i> <span>ADD TO CART</span></a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    @else
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-warning text-center rounded-0">
                                <img class="mw-100 h-100px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                                <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                            </div>
                        </div>
                    </div>                        
                    @endif
                </div>
            </div>
    </section>
    <script type="text/javascript">
        function removeFromWishlist(id) {
            $.post('{{ route('wishlists.remove') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            }, function(data) {
                $('#wishlist').html(data);
                $('#wishlist_' + id).hide();
                AIZ.plugins.notify('success', '{{ translate('Item has been renoved from wishlist') }}');
            })
        }
    </script>
@endsection
