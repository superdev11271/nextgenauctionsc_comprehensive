@extends('seller.layouts.app')

@section('panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h3 class="h3">{{ translate('Bid List') }}</h3>
            </div>
        </div>
    </div>
    <br>


    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col d-flex justify-content-between">
                <h5 class="mb-md-0 h6">{{ translate('Product Details') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <table class="table mb-0 text-center">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Reserved Price</th>
                        <th>Current Bid</th>
                        <th>Auction End</th>
                        @if ($product->sold_status)
                            <th>Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td class="w-25">{{$product->name }}</td>
                            <td>{{$product->reserved_price?$product->reserved_price:"N/A" }}</td>
                            <td>{{$product->getHighestBid()?->amount}}</td>
                            <td>{{ $product->startDate->format('j F Y h:i A') }}</td>
                            @if ($product->sold_status)
                            <td>{{$product->sold_status}}</td>
                            @endif
                        </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col d-flex justify-content-between">
                <h5 class="mb-md-0 h6">{{ translate('All Bids') }}</h5>

                @if ($product->bids->where('notified', 0)->count() && $product->sold_status == null && $product->isAuctionOver() && $product->user_id == auth()->id())
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" data-toggle="modal" data-target="#notify_bidders" data-bs-placement="top" title="Notify All bidders to Bid Higher">
                    <i class="las la-mail-bulk fs-19"></i>
                  </button>

                @endif
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ translate('Customer Name') }}</th>
                        {{-- <th>{{ translate('Email') }}</th>
                        <th>{{ translate('Phone') }}</th> --}}
                        <th>{{ translate('Bid Amount') }}</th>
                        <th>{{ translate('Date-Time') }}</th>
                        <th data-breakpoints="sm" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bids as $key => $bid)
                        @continue($bid->status != 'open')
                        <tr>
                            <td>{{ $key + 1 + ($bids->currentPage() - 1) * $bids->perPage() }}</td>
                            <td>{{ $bid->user->name }}</td>
                            {{-- <td>{{ $bid->user->email }}</td>
                            <td>{{ $bid->user->phone }}</td> --}}
                            <td>{{ single_price($bid->amount) }}</td>
                            <td>{{ $bid->created_at->diffForHumans() }}</td>
                            <td class="text-right">

                                @if ($bid->product->sold_to == $bid->user_id)
                                    <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                        title="{{ translate('Sold') }}">
                                        <i class="las la-check-double fs-5"></i>
                                    </a>
                                @endif

                                @if (
                                    $bid->product->isAuctionOver() &&
                                        $bid->product->sold_status == '' &&
                                        // !$product->passedReservedPrice() && what if reserved is met on the run time
                                        $bid->product->user_id == auth()->id())
                                    <a href="#" class="btn btn-soft-success btn-icon btn-circle btn-sm accept-bid"
                                        onclick="acceptOffer({{ $bid->id }})"
                                        title="{{ translate('Accept Offer') }}">
                                        <i class="las la-check"></i>
                                    </a>
                                    @if ($bid->notified && $bid->product->user_id == auth()->id())
                                        {{-- Chat Btn --}}
                                        <a href="{{ route('seller.chat', [$bid->product->slug, encrypt($bid->id)]) }}"
                                            class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                            title="{{ translate('Chat with ') }} {{ $bid->user->name }}">
                                            <i class="lar la-comment"></i>

                                            @php
                                                $chatCount = $bid->getUnviewdMsgCount($bid->user_id);
                                            @endphp

                                            <span class="badge bg-info text-white absolute-top-right"
                                                id="msg_badge{{ $bid->id }}"
                                                style="display: {{ $chatCount ? '' : 'none' }}">
                                                {{ $chatCount }}
                                            </span>
                                        </a>
                                    @endif
                                @endif

                                @if ($bid->product->sold_status == '')
                                    <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                        data-href="{{ route('product_bids_destroy.seller', encrypt($bid->id)) }}"
                                        title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $bids->appends(request()->input())->links() }}
            </div>
        </div>
    </div>


    <!-- Notify All Previous Bidders Modal -->
    <div id="notify_bidders" class="modal fade">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title h6">{{ translate('Notify All Bidders.') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mt-1">
                        {{ translate('This will notify all previous bidders to submit their best offers if the reserved price is not met, and it enables you to chat with them for further negotiations.') }}
                    </p>
                    <form action="{{ route('notify.bidders', encrypt($product->id)) }}" method="post">
                        @csrf
                        <button type="button" class="btn btn-link mt-2"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary mt-2">{{ translate('Notify') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal -->
@endsection

@section('modal')
    @include('modals.delete_modal')
    @include('modals.accept_bid_modal')
@endsection

@section('script')
    <script>
        function acceptOffer(id) {
            var url = '{{ route('accept.bid', ':bidId') }}'
            let newUrl = url.replace(":bidId", id)
            $("#accept_bid_modal").modal("show");
            $("#accept-link").attr("href", newUrl);
        }
    </script>
@endsection
