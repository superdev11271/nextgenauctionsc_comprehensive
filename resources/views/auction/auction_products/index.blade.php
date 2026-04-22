@extends('backend.layouts.app')
@php
$auctions = \App\Models\Product::where('auction_product', 1)
    ->select('auction_number', 'auction_label')
    ->get()
    ->unique('auction_number') // ✅ Only unique auction numbers
    ->sortBy('auction_number')
    ->values();
@endphp
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h3 class="h3">{{ translate('Auction products') }}</h3>
            </div>
            @can('add_auction_product')
                @if (Auth::user()->user_type == 'seller')
                    <div class="col text-right">
                        <a href="{{ route('auction_product_create.admin') }}" class="btn btn-circle btn-info">
                            <span>{{ translate('Add New Auction Product') }}</span>
                        </a>
                    </div>
                @endif
            @endcan
        </div>
    </div>
    <br>
    <div class="card">
        <form class="" id="sort_products" action="" method="GET">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">{{ translate('Auction Product') }}</h5>
                </div>

                @if ($type == 'seller')
                    <div class="col-md-2 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-lg-0" id="user_id"
                            name="user_id" onchange="sort_products()">
                            <option value="">{{ translate('All Sellers') }}</option>
                            @foreach (App\Models\Seller::all() as $key => $seller)
                                @if ($seller->user != null && $seller->user->shop != null)
                                    <option value="{{ $seller->user->id }}"
                                        @if ($seller->user->id == $seller_id) selected @endif>{{ $seller->user->shop?->name }}
                                        ({{ $seller->user->name }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @endif
                @if ($type == 'all')
                @can('delete_seller')
                <div class="dropdown mb-2 mb-lg-0">
                    <button class="btn border dropdown-toggle" type="button" data-toggle="dropdown">
                        {{translate('Bulk Action')}}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="javascript:void(0);" onclick="handleBulkRelist()">
                            {{ translate('Relist selection') }}
                        </a>
                    </div>
                </div>
                @endcan
              <div class="col-md-2 ml-auto">
                <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-lg-0"
                    data-live-search="true"
                    id="auction_filter"
                    name="auction_filter"
                    onchange="sort_products()">

                    <option value="">{{ translate('Select Auction by Number or Label') }}</option>

                    @foreach ($auctions as $auction)
                        @php
                            $value = $auction->auction_number;
                            $label = $auction->auction_number . ' - ' . ($auction->auction_label ?? '');
                        @endphp
                        <option value="{{ $value }}" {{ request('auction_filter') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>



                   <div class="col-md-2 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-lg-0" id="sold_status"
                            name="sold_status" onchange="sort_products()">
                            <option value="">{{ translate('Select Status') }}</option>
                            <option value="sold" {{ request('sold_status') == 'sold' ? 'selected' : '' }}>Sold</option>
                            <option value="relist" {{ request('sold_status') == 'relist' ? 'selected' : '' }}>Relist</option>
                        </select>
                    </div>


                    <div class="col-md-2 ml-auto">
                        <select class="form-control form-control-sm aiz-selectpicker mb-2 mb-lg-0" id="user_id"
                            name="user_id" onchange="sort_products()">
                            <option value="">{{ translate('All Sellers') }}</option>
                            @foreach (App\Models\User::where('user_type', '=', 'admin')->orWhere('user_type', '=', 'seller')->get() as $key => $seller)
                                <option value="{{ $seller->id }}" @if ($seller->id == $seller_id) selected @endif>
                                    {{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-md-2">
                    <div class="form-group mb-0">
                        <input type="text"
                            class="form-control form-control-sm"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="{{ translate('Type Name & Enter') }}">
                    </div>
                 </div>
            </div>

            <div class="card-body">
                <table class="table aiz-table mb-0">
                    <thead>
                        <tr>
                            <th style="width:30px;">
                            @if(auth()->user()->can('delete_seller'))
                                <div class="form-group mb-2">
                                    <div class="aiz-checkbox-inline">
                                        <label class="aiz-checkbox">
                                            <input type="checkbox" class="check-all">
                                            <span class="aiz-square-check"></span>
                                        </label>
                                    </div>
                                </div>
                            @else
                                #
                            @endif
                            </th>
                            <th>{{ translate('Name') }}</th>
                            <th data-breakpoints="sm">{{ translate('Auction No.') }}</th>
                            <th data-breakpoints="sm">{{ translate('Lot No.') }}</th>
                            @if ($type == 'all' || $type == 'seller')
                                <th data-breakpoints="sm">{{ translate('Added By') }}</th>
                            @endif
                            <th data-breakpoints="sm">{{ translate('Bid Starting Amount') }}</th>
                            <th data-breakpoints="sm">{{ translate('Auction Start Date') }}</th>
                            <th data-breakpoints="sm">{{ translate('Auction End Date') }}</th>
                            <th data-breakpoints="sm">{{ translate('Total Bids') }}</th>
                            <th data-breakpoints="sm">{{ translate('Status') }}</th>
                            @if (Auth::user()->can('can_approve_auction_products'))
                                <th data-breakpoints="lg">{{ translate('Approved') }}</th>
                            @endif
                            @if (Auth::user()->can('can_publish_auction_products'))
                                <th data-breakpoints="lg">{{ translate('Published') }}</th>
                            @endif
                            <th data-breakpoints="sm" class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            {{-- {{ dd($product) }} --}}
                            <tr>
                            <td>
                                @if(auth()->user()->can('delete_seller'))
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-one" name="id[]" value="{{encrypt($product->id)}}">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    {{ $key + 1 + ($products->currentPage() - 1) * $products->perPage() }}
                                @endif
                            </td>
                                <td>
                                    <div class="row gutters-5 w-200px w-md-300px mw-100">
                                        <div class="col-auto">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" alt="Image"
                                                class="size-50px img-fit">
                                        </div>
                                        <div class="col">
                                            <span
                                                class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->getFormattedAuctionNumber('auction_number') }}</td>
                                <td>{{ $product->lot }}</td>
                                @if ($type == 'seller' || $type == 'all')
                                    <td>{{ $product->user?->name }}</td>
                                @endif

                                <td>{{ single_price($product->starting_bid) }}</td>
                                <td class="w-110px text-center">
                                    {{-- {{ date('Y-m-d H:i:s', $product->auction_start_date) }} --}}
                                    {{ $product->startDate->format('j F Y h:i A') }}
                                </td>
                                <td class="w-110px text-center">
                                    {{-- {{ date('Y-m-d H:i:s', $product->auction_end_date) }} --}}
                                    {{ $product->endDate->format('j F Y h:i A') }}

                                </td>
                                <td>{{ $product->bids->count() }}</td>

                                <td class="btn-group-gap">

                            @if ($product->sold_status)
                                @switch($product->sold_status)
                                    @case('reclaimed')
                                        <a href="#" class="btn btn-soft-danger btn-xs default-curser">Reclaimed</a>
                                        @break

                                    @case('sold')
                                        <a href="#" class="btn btn-soft-success btn-xs default-curser">Sold</a>
                                        @break

                                    @case('moved')
                                        <a href="#" class="btn btn-soft-info btn-xs default-curser">Moved</a>
                                        @break

                                    @case('relist')
                                        <a href="#" class="btn btn-soft-info btn-xs default-curser">Relisted</a>
                                        @break
                                @endswitch

                            @elseif ($product->isLive())
                                <a href="#" class="btn btn-soft-success btn-xs default-curser">
                                    Live
                                </a>

                            @elseif ($product->isAuctionOver())
                                <a href="#" class="btn btn-soft-danger btn-xs default-curser">
                                    Auction Over
                                </a>

                            @elseif ($product->isAuctionUpcomming())
                                <a href="#" class="btn btn-soft-warning btn-xs default-curser">
                                    Upcoming
                                </a>
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


                                    {{-- @if ($product->isReclaimed())
                                        <a href="#" class="btn btn-soft-danger btn-xs">
                                            Reclaimed
                                        </a>
                                    @elseif ($product->isLive())
                                        <a href="#" class="btn btn-soft-success btn-xs">
                                            Live
                                        </a>
                                    @elseif ($product->isAuctionUpcomming())
                                        <a href="#" class="btn btn-soft-warning btn-xs">
                                            Upcomming
                                        </a>
                                    @elseif ($product->isSold())
                                        <a href="#" class="btn btn-soft-danger btn-xs">
                                            Sold
                                        </a>
                                    @elseif ($product->sold_status == 'moved')
                                        <a href="#" class="btn btn-soft-info btn-xs">
                                            Moved
                                        </a>
                                    @elseif ($product->sold_status == 'relist')
                                        <a href="#" class="btn btn-soft-info btn-xs">
                                            Relist
                                        </a>
                                    @elseif ($product->isAuctionOver())
                                        <a href="#" class="btn btn-soft-danger btn-xs">
                                            Auction over
                                        </a>
                                    @endif

                                    @if ($product->reserved_price == null)
                                        <a href="#" class="btn btn-soft-success btn-xs" title="Not Reserved">
                                            <i class="las la-exclamation-circle"></i> </a>
                                    @elseif ($product->bids->max('amount') < $product->reserved_price)
                                        <a href="#" class="btn btn-soft-danger btn-xs" title="Reserved Not Met">
                                            <i class="las la-exclamation-circle"></i> </a>
                                    @else
                                        <a href="#" class="btn btn-soft-success btn-xs" title="Reserved Met">
                                            <i class="las la-exclamation-circle"></i>
                                        </a>
                                    @endif --}}

                                </td>

                                @if (Auth::user()->can('can_approve_auction_products'))
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_approved(this,'approved')"
                                                value="{{ $product->id }}" type="checkbox"
                                                @if ($product->approved == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                @endif

                                @if (Auth::user()->can('can_publish_auction_products'))
                                    <td>
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input onchange="update_approved(this,'published')"
                                                value="{{ $product->id }}" type="checkbox"
                                                @if ($product->published == 1) checked @endif>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                @endif

                                <td class="text-right btn-group-gap text-nowrap">
                                    <div class="dropdown">
                                        <button class="btn btn-soft-primary btn-icon btn-circle btn-sm " type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right"
                                            aria-labelledby="dropdownMenuButton">
                                            @if ($product->auction_start_date > strtotime('now') && auth()->user()->can('edit_auction_product'))
                                                <a class="dropdown-item"
                                                    href="{{ route('auction_product_edit.admin', ['id' => encrypt($product->id), 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Edit') }}">
                                                    <i class="las la-edit"></i> {{ translate('Edit') }}
                                                </a>
                                            @endif

                                            @if ($product->sold_status == null && $product->isAuctionOver() && (!$product->passedReservedPrice() || $product->reserved_price==null))
                                                <a class="dropdown-item"
                                                    href="{{ route('auction.relist_auction', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Relist') }}">
                                                    <i class="las la-clone"></i> {{ translate('Relist') }}
                                                </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('auction.move_to_marketplace_form', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Move to Marketplace') }}">
                                                    <i class="las la-exchange-alt"></i>
                                                    {{ translate('Move to Marketplace') }}
                                                </a>
                                                <a class="dropdown-item confirm-reclaim" href="#"
                                                    data-href="{{ route('auction_product_reclaimed.admin', $product->id) }}"
                                                    title="{{ translate('Reclaim') }}">
                                                    <i class="las la-undo"></i> {{ translate('Reclaim') }}
                                                </a>
                                            @endif

                                            @if ($product->isAuctionStarted())
                                                @can('view_auction_product_bids')
                                                 <a class="dropdown-item"
                                                    href="{{ route('auction.relist_auction', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Relist') }}">
                                                    <i class="las la-clone"></i> {{ translate('Relist') }}
                                                </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('product_bids.admin', encrypt($product->id)) }}"
                                                        target="_blank" title="{{ translate('View All Bids') }}">
                                                        <i class="las la-gavel"></i> {{ translate('View All Bids') }}
                                                    </a>
                                                <a class="dropdown-item"
                                                    href="{{ route('auction_product_edit.admin', ['id' => encrypt($product->id), 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                    title="{{ translate('Edit') }}">
                                                    <i class="las la-edit"></i> {{ translate('Edit') }}
                                                </a>
                                                  <a class="dropdown-item confirm-delete" href="#"
                                                        data-href="{{ route('auction_product_destroy.admin', encrypt($product->id)) }}"
                                                        title="{{ translate('Delete') }}">
                                                        <i class="las la-trash"></i> {{ translate('Delete') }}
                                                    </a>
                                                @endcan
                                            @endif

                                            @if (!$product->isAuctionStarted())
                                                @can('delete_auction_product')
                                                    <a class="dropdown-item confirm-delete" href="#"
                                                        data-href="{{ route('auction_product_destroy.admin', encrypt($product->id)) }}"
                                                        title="{{ translate('Delete') }}">
                                                        <i class="las la-trash"></i> {{ translate('Delete') }}
                                                    </a>
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- <td class="text-right btn-group-gap text-nowrap">
                                    @if ($product->auction_start_date > strtotime('now') && auth()->user()->can('edit_auction_product'))
                                        <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                            href="{{ route('auction_product_edit.admin', ['id' => encrypt($product->id), 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                            title="{{ translate('Edit') }}">
                                            <i class="las la-edit"></i>
                                        </a>
                                    @endif
                                    @if (!env('HIDDEN_UNDERDEVELOPMENT_FUNCS'))
                                        @if ($product->isAuctionOver() && !$product->passedReservedPrice())
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('auction.relist_auction', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Relist') }}">
                                                <i class="las la-clone"></i>
                                            </a>
                                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                                href="{{ route('auction.move_to_marketplace_form', ['id' => $product->id, 'lang' => env('DEFAULT_LANGUAGE')]) }}"
                                                title="{{ translate('Move to Marketplace') }}">
                                                <i class="las la-exchange-alt"></i>
                                            </a>
                                            <a href="#"
                                                class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-reclaim"
                                                data-href="{{ route('auction_product_reclaimed.admin', $product->id) }}"
                                                title="{{ translate('Reclaim') }}">
                                                <i class="las la-undo"></i>
                                            </a>
                                        @endif
                                    @endif
                                    @can('view_auction_product_bids')
                                        <a class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            href="{{ route('product_bids.admin', encrypt($product->id)) }}" target="_blank"
                                            title="{{ translate('View All Bids') }}">
                                            <i class="las la-gavel"></i>
                                        </a>
                                    @endcan

                                    @can('delete_auction_product')
                                        <a href="#"
                                            class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                            data-href="{{ route('auction_product_destroy.admin', encrypt($product->id)) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $products->appends(request()->input())->links() }}
                </div>
        </form>
    </div>

    <form id="bulk-relist-form" method="POST" action="{{ route('auction.bulk_relist_auction_form') }}">
        @csrf
        <input type="hidden" name="ids[]" id="bulk-relist-ids">
    </form>

@endsection

@section('modal')
    @include('modals.delete_modal')
    @include('modals.reclaim_modal')
@endsection


@section('script')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

    // let selectedIds = [];

    function sort_products(el) {
        $('#sort_products').submit();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    document.getElementById('sort_products').submit();
                }
            });
        }
    });

    function update_approved(el, type) {
        let status = el.checked ? 1 : 0;
        $.post('{{ route('auction.products.updates') }}', {
            _token: '{{ csrf_token() }}',
            id: el.value,
            update_type: type,
            status: status
        }, function (data) {
            if (data == 1) {
                AIZ.plugins.notify('success', `{{ translate('Product ${type} successfully') }}`);
            } else {
                AIZ.plugins.notify('danger', `{{ translate('Product un${type} successfully') }}`);
            }
        });
    }

// $(document).on("change", ".check-one", function () {
//     const id = $(this).val();

//     if ($(this).is(':checked')) {
//         if (!selectedIds.includes(id)) {
//             selectedIds.push(id);
//         }
//     } else {
//         selectedIds = selectedIds.filter(val => val !== id);
//     }
// });


// $(document).on("change", ".check-all", function () {
//     const isChecked = $(this).is(':checked');

//     $('.check-one').each(function () {
//         const id = $(this).val();
//         this.checked = isChecked;

//         if (isChecked) {
//             if (!selectedIds.includes(id)) {
//                 selectedIds.push(id);
//             }
//         } else {
//             selectedIds = selectedIds.filter(val => val !== id);
//         }
//     });
// });


// $(document).ready(function () {
//     $('.check-one').each(function () {
//         const id = $(this).val();
//         if (selectedIds.includes(id)) {
//             $(this).prop('checked', true);
//         }
//     });
// });


//  function handleBulkRelist() {
//     if (selectedIds.length === 0) {
//         Swal.fire({
//             icon: 'warning',
//             title: 'No product selected',
//             text: 'Please select at least one product to relist.',
//         });
//         return;
//     }

//     Swal.fire({
//         title: 'Are you sure?',
//         text: "You are about to relist selected products.",
//         icon: 'warning',
//         showCancelButton: true,
//         confirmButtonColor: '#3085d6',
//         cancelButtonColor: '#aaa',
//         confirmButtonText: 'Yes, Relist!'
//     }).then((result) => {
//         if (result.isConfirmed) {
//             $('#bulk-relist-form input[name="ids[]"]').remove();

//             selectedIds.forEach(function (id) {
//                 $('#bulk-relist-form').append(
//                     $('<input>').attr({
//                         type: 'hidden',
//                         name: 'ids[]',
//                         value: id
//                     })
//                 );
//             });

//             $('#bulk-relist-form').submit();
//         }
//     });
// }



</script>

<script>
    let selectedIds = JSON.parse(localStorage.getItem('selectedIds')) || [];

    // Log existing IDs when page loads
    console.log('Loaded selected IDs:', selectedIds);

    // Restore checkbox checked state based on saved IDs
    $(document).ready(function () {
        $('.check-one').each(function () {
            const id = $(this).val();
            if (selectedIds.includes(id)) {
                $(this).prop('checked', true);
            }
        });

        // If all checkboxes on this page are selected, check ".check-all"
        const allChecked = $('.check-one').length === $('.check-one:checked').length;
        $('.check-all').prop('checked', allChecked);
    });

    // Handle individual checkbox change
    $(document).on("change", ".check-one", function () {
        const id = $(this).val();

        if ($(this).is(':checked')) {
            if (!selectedIds.includes(id)) {
                selectedIds.push(id);
                console.log('Checked:', id);
            }
        } else {
            selectedIds = selectedIds.filter(val => val !== id);
            console.log('Unchecked:', id);
        }

        localStorage.setItem('selectedIds', JSON.stringify(selectedIds));

        // Check or uncheck the "check-all" box based on current page
        const allChecked = $('.check-one').length === $('.check-one:checked').length;
        $('.check-all').prop('checked', allChecked);
    });

    // Handle "Check All" change
    $(document).on("change", ".check-all", function () {
        const isChecked = $(this).is(':checked');

        $('.check-one').each(function () {
            const id = $(this).val();
            $(this).prop('checked', isChecked);

            if (isChecked) {
                if (!selectedIds.includes(id)) {
                    selectedIds.push(id);
                    console.log('Checked (all):', id);
                }
            } else {
                selectedIds = selectedIds.filter(val => val !== id);
                console.log('Unchecked (all):', id);
            }
        });

        localStorage.setItem('selectedIds', JSON.stringify(selectedIds));
    });

    // Handle bulk relist submit
    function handleBulkRelist() {
        console.log('Bulk Relist Clicked. Selected IDs:', selectedIds);

        if (selectedIds.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No product selected',
                text: 'Please select at least one product to relist.',
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You are about to relist selected products.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, Relist!'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#bulk-relist-form input[name="ids[]"]').remove();

                selectedIds.forEach(function (id) {
                    $('#bulk-relist-form').append(
                        $('<input>').attr({
                            type: 'hidden',
                            name: 'ids[]',
                            value: id
                        })
                    );
                });

                // Optional: clear localStorage
                localStorage.removeItem('selectedIds');
                selectedIds = [];

                $('#bulk-relist-form').submit();
            }
        });
    }

    window.handleBulkRelist = handleBulkRelist;
</script>


@endsection

