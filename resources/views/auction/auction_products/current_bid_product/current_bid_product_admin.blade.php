@extends('backend.layouts.app')

@section('content')
    <div class="card">


        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{translate('Name')}}</th>
                        <th data-breakpoints="sm">{{translate('Auction No.')}}</th>
                        <th data-breakpoints="sm">{{translate('Lot No.')}}</th>

                        <th data-breakpoints="sm">{{translate('Added By')}}</th>

                        <th data-breakpoints="sm">{{translate('Bid Starting Amount')}}</th>
                        <th data-breakpoints="sm">{{translate('Auction Start Date')}}</th>
                        <th data-breakpoints="sm">{{translate('Auction End Date')}}</th>
                        @if(get_setting('product_approve_by_admin') == 1 && $type == 'seller')
                            <th data-breakpoints="lg">{{translate('Approved')}}</th>
                        @endif
                        <th data-breakpoints="sm" class="text-right">{{translate('Options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- {{ dd($products) }} --}}
                    @foreach ($products as $key => $product)
                        @if ($products != null)
                            <tr>
                                {{-- {{ dd($product) }} --}}
                                <td>{{ ($key+1)  }}</td>
                                <td>
                                    <div class="row gutters-5 w-200px w-md-300px mw-100">
                                        <div class="col-auto">
                                            <img src="{{ uploaded_asset($product->thumbnail_img)}}" alt="Image" class="size-50px img-fit">
                                        </div>
                                        <div class="col">
                                            <span class="text-muted text-truncate-2">{{ $product->getTranslation('name') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->auction_number }}</td>
                        <td>{{ $product->lot }}</td>

                        <td>{{ $product->added_by }}</td>


                        <td>{{ single_price($product->starting_bid) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $product->auction_start_date) }}</td>
                        <td>{{ date('Y-m-d H:i:s', $product->auction_end_date) }}</td>

                        <td class="text-right">

                            @can('view_auction_product_bids')
                                <a class="btn btn-soft-info btn-icon btn-circle btn-sm"  href="{{ route('product_bids.admin', encrypt($product->product_id)) }}" target="_blank" title="{{ translate('View All Bids') }}">
                                    <i class="las la-gavel"></i>
                                </a>
                            @endcan

                        </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
