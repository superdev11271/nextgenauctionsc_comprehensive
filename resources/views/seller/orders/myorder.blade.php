@extends('seller.layouts.app')

@section('panel_content')

    <div class="card">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="card-header row gutters-5">
                <div class="col text-center text-md-left mb-2 mb-md-0">
                    <h5 class="mb-md-0 h6">{{ translate('purchase History') }}</h5>
                </div>
                <div class="col-md-3 mb-2 mb-md-0">
                    <div class="from-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                    </div>
                </div>
            </div>
        

            @if (count($orders) > 0)
                <div class="card-body p-3">
                    <table class="table aiz-table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ translate('Order Code') }}</th>
                                <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                                <th data-breakpoints="md">{{ translate('Amount') }}</th>
                                <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                                <th>{{ translate('Payment Status') }}</th>
                                <th class="text-right">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key => $order_id)
                                @php
                                    $order = \App\Models\Order::find($order_id->id);
                                @endphp
                                @if ($order != null)
                                    <tr>
                                        <td>
                                            {{ ($key+1) + ($orders->currentPage() - 1)*$orders->perPage() }}
                                        </td>
                                        <td>
                                            <a href="#{{ $order->code }}"
                                                onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                            @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                                <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if ($order->user_id != null)
                                                {{ optional($order->user)->name }}@if($order->user_id == Auth::user()->id) ({{ __('You')}}) @endif
                                            @else
                                                {{ translate('Guest') }} ({{ $order->guest_id }})
                                            @endif
                                        </td>
                                        <td>
                                            {{ single_price($order->grand_total) }}
                                        </td>
                                        <td>
                                            @php
                                                $status = $order->delivery_status;
                                            @endphp
                                            {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                        </td>
                                        <td>
                                            @if ($order->payment_status == 'paid')
                                                <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                            @else
                                                <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                            @endif
                                        </td>
                                        <td class="text-nowrap">
                                            @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                                <a class="btn btn-soft-success btn-icon btn-circle btn-sm"
                                                    href="{{ route('seller.invoice.thermal_printer', $order->id) }}"
                                                    target="_blank" title="{{ translate('Thermal Printer') }}">
                                                    <i class="las la-print"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('seller.orders.show', encrypt($order->id)) }}"
                                                class="btn btn-soft-info btn-icon btn-circle btn-sm"
                                                title="{{ translate('Order Details') }}">
                                                <i class="las la-eye"></i>
                                            </a>
                                            <a href="{{ route('seller.invoice.download', encrypt($order->id)) }}"
                                                class="btn btn-soft-warning btn-icon btn-circle btn-sm"
                                                title="{{ translate('Download Invoice') }}">
                                                <i class="las la-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $orders->links() }}
                    </div>
                </div>
            @endif
        </form>
    </div>

@endsection

@section('script')
    <script type="text/javascript">

        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }
        });

        function sort_orders(el) {
            $('#sort_orders').submit();
        }
    </script>
@endsection