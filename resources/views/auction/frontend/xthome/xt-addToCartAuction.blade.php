<div class="modal-body p-4 c-scrollbar-light">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                @php
                    $photos = explode(',',$product->photos);
                @endphp
                <div class="col w-75">
                    <div class="position-relative pe-md-4">
                    <div class="product-gallery mb-4 mb-md-0">
                        @if($product->thumbnail_img)
                        <div class="rounded-thumb-img">
                            <img class="img-fluid lazyload"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                        @endif
                        @foreach ($photos as $key => $photo)
                        <div class="rounded-thumb-img">
                            <img class="img-fluid lazyload"
                                src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                data-src="{{ uploaded_asset($photo) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        </div>
                        @endforeach
                        @foreach ($product->stocks as $key => $stock)
                            @if ($stock->image != null)
                                <div class="rounded-thumb-img">
                                    <img class="img-fluid lazyload"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            @endif
                        @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="text-left">
                <h5 class="fs-20 fw-700 price-head text-break">
                    {{  $product->getTranslation('name')  }}
                </h5>
                <div class="row no-gutters mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-3 align-items-center">
                            <div>{{ translate('Price')}}:</div>
                            <div class="price-head">
                                <span class="h3 fw-600">
                                    {{ single_price($product->bids->max('amount')) }}
                                </span>
                                <span>/{{ $product->unit }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                @php
                    $qty = 0;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                @endphp

                <form id="option-choice-form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1" >
                </form>
                <div class="mt-3">
                    <button type="button" class="input-group-text theme-btn-card px-3" onclick="addToCart()">
                        <i class="fa-solid fa-cart-shopping m-0"></i>
                        <span class="d-none d-md-inline-block"> {{ translate('Add to cart')}}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.product-gallery').slick({
            slidesToShow: 1,
            arrows: true,
            fade: false,
            autoplay: true,
            speed: 500,
            prevArrow: '<div class="slick-prev"><i class="i-prev"></i><span class="sr-only sr-only-focusable">Previous</span></div>',
            nextArrow: '<div class="slick-next"><i class="i-next"></i><span class="sr-only sr-only-focusable">Next</span></div>'
        });

        const cartmodal = document.getElementById('addToCart');
        cartmodal.addEventListener('show.bs.modal', event => {
            $('.product-gallery').slick('refresh');
        });
        $('.product-gallery').slick('refresh');
    });
</script>
