@php
$cart_added = [];
$auction_detail = $product->first();
$index = 0;

while ($auction_detail && $auction_detail->isAuctionOver()) {
$index++;
$auction_detail = $product->skip($index)->first();
}

$product_url = route('auction_product_collection', $auction_detail['auction_number']??'');

$currentTime = strtotime('now');
$sortedProducts = $product->sortBy(function($data) use ($currentTime) {
$isOngoing = $data->auction_start_date <= $currentTime && $data->auction_end_date >= $currentTime;
    return $isOngoing ? 0 : 1; // Live (0) products come first, Upcoming (1) come later
    });

    @endphp
    <style>
        .theme-btn-card:hover {
            color: white;
            /* Change the text color to white on hover */
        }
    </style>

    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="shop-block-one d">
            <div class="inner-box position-relative pb-0">
                <div class="position-relative h-100">
                    {{-- <div class="card-carousel owl-theme owl-carousel"> --}}
                    {{-- @foreach ($sortedProducts as $data) --}}
                    {{-- @php
                            $route_name = $data->auction_product == 1 ? 'auction-product' : 'product';
                            $product_url = route("$route_name", $data->slug);
                        @endphp --}}
                    {{-- <div class="slide-item"> --}}
                    {{-- Auction indigator --}}
                    @php
                    $data =$sortedProducts[0];
                    $currentTime = strtotime('now');
                    $isUpcoming = $data->auction_start_date > $currentTime;
                    $isOngoing = $data->auction_start_date <= $currentTime && $data->auction_end_date >= $currentTime; // Ongoing auction
                        @endphp

                        @if ($data->auction_product == 1)
                        @if($isOngoing)
                        <span class="runing text-dark" style="right:38px;">Live</span>
                        @elseif($isUpcoming)
                        <span class="upcoming text-dark" style="right:38px;">Soon</span>
                        @else
                        <span class="upcoming text-dark" style="right:38px;">end</span>
                        @endif
                        @endif
                        <a href="{{ route('auction_collection_products.all', encrypt($auction_detail->auction_number)) }}">
                            @if ($data?->banner_image)
                            <img src="{{ uploaded_asset($data?->banner_image) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" class="position-relative h-100 w-100 object-fit-cover" alt="">
                            @else
                            <img src="{{ get_image($data->thumbnail, 'thumbnail') }}"
                                alt="{{ $data->getTranslation('name') }}" title="{{ $data->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" />
                            @endif
                        </a>
                        {{-- </div> --}}
                        {{-- @endforeach --}}
                        {{-- </div> --}}
                </div>

                <div class="lower-content auctionCardBottomBox">
                    <span style="float:right">Total Lots - {{ $product->count() }}</span>
                    <a href="{{ route('auction_collection_products.all', encrypt($auction_detail->auction_number)) }}">
                        <h6 class="pt-1">{{ $auction_detail->getCollectionLabel() }}</h6>
                    </a>
                    <a href="{{ route('auction_collection_products.all', encrypt($auction_detail->auction_number)) }}">
                        <h6 class="pt-2">{{ ucfirst($auction_detail->getTranslation('name')) }}</h6>
                    </a>
                </div>


                <div class="p-2 text-justify">
                    @if($isOngoing)
                    {{-- Ongoing Counter --}}
                    <div class="d-flex gap-2 pb-2" id="timer">
                        <span class="auction-timer auction-status-text  auction-timer-{{ $auction_detail->id }}"
                            data-endunixtime="{{ $auction_detail->auction_end_date }}"
                            data-end="{{ date('Y/m/d H:i:s', $auction_detail->auction_end_date) }}">
                            {{ date('Y/m/d H:i:s', $auction_detail->auction_end_date) }}
                        </span>
                    </div>
                    <div class="text-white large">
                        <span class="end-date">
                            {{ \Carbon\Carbon::createFromTimestamp($auction_detail->auction_end_date)->setTimezone(config('app.timezone'))->format('l jS F Y') }}
                        </span>
                    </div>
                    @else
                    {{-- Upcoming Counter --}}
                    <div class="d-flex gap-2 pb-2" id="timer">
                        <span class="auction-timer auction-status-text  auction-timer-{{ $auction_detail->id }}"
                            data-start="{{ date('Y/m/d H:i:s', $auction_detail->auction_start_date) }}"
                            data-startunixtime="{{ $auction_detail->auction_start_date }}"
                            data-end="{{ date('Y/m/d H:i:s', $auction_detail->auction_end_date) }}"
                            data-endunixtime="{{ $auction_detail->auction_end_date }}">
                            {{ date('Y/m/d H:i:s', $auction_detail->auction_start_date) }}
                        </span>
                    </div>
                    <div class="text-white large">
                        <span class="end-date">
                            {{ \Carbon\Carbon::createFromTimestamp($auction_detail->auction_start_date)->setTimezone(config('app.timezone'))->format('l jS F Y') }}
                        </span>
                    </div>
                    @endif
                </div>
                @if ($auction_detail && $auction_detail->auction_end_date > strtotime('now'))
                <div class="pb-2 lower-content">
                    @if (Auth::check() && $auction_detail->user_id == Auth::user()->id)
                    <span
                        class="py-2 badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                    @elseif (Auth::check() && Auth::user()->user_type == 'admin')
                    <span
                        class="py-2 badge badge-inline badge-danger">{{ translate("Admin cannot Place Bid  to Sellers' Product.") }}</span>
                    @else
                    <a class="theme-btn-card btn-sm w-100 text-uppercase"
                        href="{{ route('auction_collection_products.all', encrypt($auction_detail->auction_number)) }}">View
                        Auction</a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
