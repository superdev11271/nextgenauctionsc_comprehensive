@extends('seller.layouts.app')

@php
    $packageInvalidAt = \Carbon\Carbon::parse(Auth::user()->shop?->package_invalid_at);
    $now = \Carbon\Carbon::now();
    $isExpired = $packageInvalidAt < $now;
    $daysRemaining = $now->diffInDays($packageInvalidAt, false); // false ensures negative values if expired
@endphp

@section('panel_content')
    <div class="row gutters-10 justify-content-center">

        <div class="col-md-4 mx-auto mb-3">
            <a href="{{ route('auction_product_create.seller') }}">
                <div class="p-3 rounded mb-3 c-pointer text-center bg-white shadow-sm hov-shadow-lg has-transition">
                    <span
                        class="size-60px rounded-circle mx-auto bg-secondary d-flex align-items-center justify-content-center mb-3">
                        <i class="las la-plus la-3x text-white"></i>
                    </span>
                    <div class="fs-18 text-primary">{{ translate('Add New Auction Product') }}</div>
                </div>
            </a>
        </div>
    </div>
    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col  pb-2 pb-md-0">
                    <h5 class="mb-md-0 h6">{{ translate('All Auction Product') }}</h5>
                </div>

                <div class="col-md-2">
                    <div class="form-group  pb-2 pb-md-0">
                        <input type="text" class="form-control form-control-sm" id="search"
                            name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Name & Enter') }}">
                    </div>
                </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Name') }}</th>
                            <th data-breakpoints="sm">{{ translate('Auction No.') }}</th>
                            <th data-breakpoints="sm">{{ translate('Lot No.') }}</th>
                            <th data-breakpoints="sm">{{ translate('Bid Starting Amount') }}</th>
                            <th data-breakpoints="sm">{{ translate('Start Date') }}</th>
                            <th data-breakpoints="sm">{{ translate('End Date') }}</th>
                            <th data-breakpoints="sm">{{ translate('Total Bids') }}</th>
                            {{-- <th data-breakpoints="sm">{{ translate('Reserved/Current BId') }}</th> --}}
                            <th data-breakpoints="sm">{{ translate('Status') }}</th>
                            <th data-breakpoints="sm" class="text-right" width="20%">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            <tr>
                                <td>{{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}</td>
                                <td style="overflow-wrap:anywhere">
                                    <div class="row gutters-5 w-200px w-md-300px mw-100">
                                        <div class="col-auto">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image"
                                                class="size-50px img-fit">
                                        </div>
                                        <a class="ml-3" href="{{ route('auction-product', $product->slug) }}"
                                            target="_blank">
                                            {{ $product->getTranslation('name') }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    {{ $product->getFormattedAuctionNumber('auction_number') }}
                                </td>
                                <td>{{ $product->lot }}</td>
                                <td>{{ single_price($product->starting_bid) }}</td>
                                <td class="w-110px text-center">
                                    {{-- {{ date('Y-m-d H:i:s', $product->auction_start_date) }} --}}
                                    {{ $product->startDate->format('j F Y h:i A') }}
                                </td>
                                <td class="w-110px text-center">
                                    {{-- {{ date('Y-m-d H:i:s', $product->auction_end_date) }} --}}
                                    {{ $product->endDate->format('j F Y h:i A') }}
                                </td>
                                <td class="text-center">{{ $product->bids->count() }}</td>
                                {{-- <td>{{$product->reserved_price==null?:$product->reserved_price."/"}}{{$product->bids->max('amount')??0}}</td> --}}

                                <td class="text-center">

                                    @if (!$product->published || !$product->approved)
                                    <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip" title="Admin approval is pending for this product."></i>
                                    @endif
                                    @if ($product->sold_status == null)
                                        @if ($product->isLive())
                                            <a href="#" class="btn btn-soft-success btn-xs default-curser">
                                                Live
                                            </a>
                                        @elseif ($product->isAuctionUpcomming())
                                            <a href="#" class="btn btn-soft-danger btn-xs default-curser">
                                                Upcomming
                                            </a>
                                        @elseif ($product->isAuctionOver())
                                            <a href="#" class="btn btn-soft-danger btn-xs default-curser">
                                                Auction over
                                            </a>
                                        @endif
                                    @else
                                        @if ($product->sold_status == 'reclaimed')
                                            <a href="#" class="btn btn-soft-danger btn-xs default-curser">
                                                Reclaimed
                                            </a>
                                        @elseif ($product->sold_status == 'sold')
                                            <a href="#" class="btn btn-soft-success btn-xs default-curser">
                                                Sold
                                            </a>
                                        @elseif ($product->sold_status == 'moved')
                                            <a href="#" class="btn btn-soft-info btn-xs default-curser">
                                                Moved
                                            </a>
                                        @elseif ($product->sold_status == 'relist')
                                            <a href="#" class="btn btn-soft-info btn-xs default-curser">
                                                Relist
                                            </a>
                                        @endif
                                    @endif


                                    @if ($product->sold_status == null && $product->reserved_price != null)
                                        @if ($product->reserved_price == null)
                                            <span class="btn btn-soft-success btn-xs default-curser" title="Not Reserved">
                                                <i class="las la-thumbs-up"></i>
                                            </span>
                                        @elseif ($product->bids->max('amount') < $product->reserved_price)
                                            <a href="#" class="btn btn-soft-danger btn-xs default-curser"
                                                title="Reserved Not Met">
                                                <i class="las la-exclamation-circle"></i> </a>
                                        @else
                                            <a href="#" class="btn btn-soft-success btn-xs default-curser"
                                                title="Reserved Met">
                                                <i class="las la-thumbs-up"></i>
                                            </a>
                                        @endif
                                    @endif

                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-icon btn-circle btn-sm " type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            @if ($product->auction_start_date > strtotime('now'))
                                                <a class="dropdown-item"
                                                    href="{{ route('auction_product_edit.seller', ['id' => encrypt($product->id), 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Edit') }}">
                                                    <i class="las la-edit"></i>{{ translate('Edit') }}
                                                </a>
                                            @endif

                                            @if (
                                                $product->sold_status == null &&
                                                    $product->isAuctionOver() &&
                                                    $product->bids->max('amount') < $product->reserved_price)
                                                <a class="dropdown-item"
                                                    href="{{ route('auction.seller_relist_auction', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Relist') }}">
                                                    <i class="las la-clone"></i>{{ translate('Relist') }}
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('auction.seller_move_to_marketplace_form', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Move to Marketplace') }}">
                                                    <i
                                                        class="las la-exchange-alt"></i>{{ translate('Move to Marketplace') }}
                                                </a>
                                                <a href="#" class="dropdown-item confirm-reclaim"
                                                    data-href="{{ route('auction_product_reclaimed.seller', $product->id) }}"
                                                    title="{{ translate('Reclaim') }}">
                                                    <i class="las la-undo"></i>{{ translate('Reclaim') }}
                                                </a>
                                            @endif
                                            @if ($product->isAuctionStarted())
                                                <a class="dropdown-item"
                                                    href="{{ route('product_bids.seller', encrypt($product->id)) }}"
                                                    target="_blank" title="{{ translate('View All Bids') }}">
                                                    <i class="las la-gavel"></i>{{ translate('View All Bids') }}
                                                </a>
                                            @endif
                                            @if (!$product->isAuctionStarted())
                                                <a href="#" class="dropdown-item confirm-delete"
                                                    data-href="{{ route('auction_product_destroy.seller', encrypt($product->id)) }}"
                                                    title="{{ translate('Delete') }}">
                                                    <i class="las la-trash"></i>{{ translate('Delete') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
    @include('modals.reclaim_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function sort_products(el) {
            $('#sort_products').submit();
        }
    </script>
@endsection
