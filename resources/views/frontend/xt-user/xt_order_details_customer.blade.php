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
                    <div class="card mb-5">

                        <!-- Order id -->
                        <div class="aiz-titlebar mb-4">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="fs-20 fw-700 text-dark">{{ translate('Order id') }}: {{ $order->code }}</h5>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="card rounded-0 shadow-none mb-4">
                            <div class="card-header border-bottom-0">
                                <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Summary') }}</h5>
                            </div>
                            <div class="light-dark-bg px-4 p-4 mt-3 text-gray">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Order Code') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">{{ $order->code }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Customer') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                @php
                                                    $shippingAddress = json_decode($order->shipping_address);
                                                @endphp
                                                <p class="fs-16 fw-500 mb-0">{{ $shippingAddress->name ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Email') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                @if ($order->user_id != null)
                                                    <p class="fs-16 fw-500 mb-0">{{ $order->user->email }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-0 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Shipping address') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                @php
                                                    $shipping_address = json_decode($order->shipping_address);
                                                @endphp
                                                {{ isset($shipping_address->address) ? $shipping_address->address . ',' : '' }}
                                                {{ isset($shipping_address->city) ? $shipping_address->city . ',' : '' }}
                                                {{ isset($shipping_address->state) ? $shipping_address->state . '-' : '' }}
                                                {{ isset($shipping_address->postal_code) ? $shipping_address->postal_code . ',' : '' }}
                                                {{ isset($shipping_address->country) ? $shipping_address->country : '' }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-0 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Billing address') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                @php
                                                    $billing_address = json_decode($order->billing_address);
                                                @endphp
                                                {{ isset($billing_address->address) ? $billing_address->address . ',' : '' }}
                                                {{ isset($billing_address->city) ? $billing_address->city . ',' : '' }}
                                                {{ isset($billing_address->state) ? $billing_address->state . '-' : '' }}
                                                {{ isset($billing_address->postal_code) ? $billing_address->postal_code . ',' : '' }}
                                                {{ isset($billing_address->country) ? $billing_address->country : '' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Order date') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">{{ date('d-m-Y H:i A', $order->date) }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Order status') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">
                                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Total order amount') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">
                                                    {{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Shipping method') }}:</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">
                                                    @if ($order->shipping_type == 'home_delivery')
                                                        {{ translate('Home Delivery') }}
                                                    @elseif ($order->shipping_type == 'pickup_point')
                                                        {{ translate('Pick Up') }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-1 py-2">
                                            <div>
                                                <p class="fs-16 fw-500 mb-0">{{ translate('Additional Info') }}</p>
                                            </div>
                                            <div class="fs-16">
                                                <p class="fs-16 fw-500 mb-0">{{ $order->additional_info }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between border-bottom-0 py-2">
                                            @if ($order->tracking_code)
                                                <div>
                                                    <p class="fs-16 fw-500 mb-0">{{ translate('Tracking code') }}:</p>
                                                </div>
                                                <div class="fs-16">
                                                    <p class="fs-16 fw-500 mb-0">{{ $order->tracking_code }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="row gutters-16">
                            <div class="col-md-12">
                                <div class="card rounded-0 shadow-none mt-2 mb-4">
                                    <div class="card-header border-bottom-0">
                                        <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Order Details') }}</h5>
                                    </div>
                                    <div class="light-dark-bg px-4 p-4 mt-3 text-gray">
                                        <div class="table-responsive">
                                            <table class="aiz-table table">
                                                <thead class="text-gray fs-12">
                                                    <tr>
                                                        <th class="pl-0">#</th>
                                                        <th width="30%">{{ translate('Product') }}</th>
                                                        <th data-breakpoints="md">{{ translate('Variation') }}</th>
                                                        <th>{{ translate('Quantity') }}</th>
                                                        <th data-breakpoints="md">{{ translate('Delivery Type') }}</th>
                                                        <th>{{ translate('Price') }}</th>
                                                        @if (addon_is_activated('refund_request'))
                                                            <th data-breakpoints="md">{{ translate('Refund') }}</th>
                                                        @endif
                                                        <th data-breakpoints="md" class="text-right pr-0">
                                                            {{ translate('Review') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-14">
                                                    @foreach ($order->orderDetails as $key => $orderDetail)
                                                        <tr>
                                                            <td class="pl-0">{{ sprintf('%02d', $key + 1) }}</td>
                                                            <td>
                                                                @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                                                    <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                                        target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                                                @elseif($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                                                    <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                                        target="_blank">{{ $orderDetail->product->getTranslation('name') }}</a>
                                                                @else
                                                                    <strong>{{ translate('Product Unavailable') }}</strong>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                {{ $orderDetail->variation }}
                                                            </td>
                                                            <td>
                                                                {{ $orderDetail->quantity }}
                                                            </td>
                                                            <td>
                                                                @if ($orderDetail?->shipping_type == 'home_delivery')
                                                                    {{ translate('Home Delivery') }}
                                                                @elseif ($orderDetail?->shipping_type == 'pickup_point')
                                                                    {{ translate('Pickup Point') }}
                                                                @endif
                                                            </td>
                                                            <td class="fw-700">{{ single_price($orderDetail->price) }}</td>
                                                            @if (addon_is_activated('refund_request'))
                                                                @php
                                                                    $no_of_max_day = get_setting('refund_request_time');
                                                                    $last_refund_date = $orderDetail->created_at->addDays(
                                                                        $no_of_max_day,
                                                                    );
                                                                    $today_date = Carbon\Carbon::now();
                                                                @endphp
                                                                <td>
                                                                    @if (
                                                                        $orderDetail->product != null &&
                                                                            $orderDetail->product->refundable != 0 &&
                                                                            $orderDetail->refund_request == null &&
                                                                            $today_date <= $last_refund_date &&
                                                                            $orderDetail->payment_status == 'paid' &&
                                                                            ($orderDetail->delivery_status == 'delivered' || $orderDetail->delivery_status == 'picked_up'))
                                                                        <a href="{{ route('refund_request_send_page', $orderDetail->id) }}"
                                                                            class="btn btn-primary btn-sm rounded-0">{{ translate('Send') }}</a>
                                                                    @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 0)
                                                                        <b class="text-info">{{ translate('Pending') }}</b>
                                                                    @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 2)
                                                                        <b
                                                                            class="text-success">{{ translate('Rejected') }}</b>
                                                                    @elseif ($orderDetail->refund_request != null && $orderDetail->refund_request->refund_status == 1)
                                                                        <b
                                                                            class="text-success">{{ translate('Approved') }}</b>
                                                                    @elseif ($orderDetail->product->refundable != 0)
                                                                        <b>{{ translate('N/A') }}</b>
                                                                    @else
                                                                        <b>{{ translate('Non-refundable') }}</b>
                                                                    @endif
                                                                </td>
                                                            @endif
                                                            <td class="text-right pr-0">

                                                                @if ($orderDetail->delivery_status == 'picked_up' || $orderDetail->delivery_status == 'delivered')
                                                                    <a href="javascript:void(0);"
                                                                        onclick="product_review('{{ $orderDetail->product_id }}')"
                                                                        class="btn btn-primary btn-sm rounded-0">
                                                                        {{ translate('Review') }} </a>
                                                                @else
                                                                    <span
                                                                        class="text-danger">{{ translate('Not Delivered Yet') }}</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Order Ammount -->
                            <div class="col-md-12">
                                <div class="card rounded-0 shadow-none mt-2">
                                    <div class="card-header border-bottom-0">
                                        <h5>{{ translate('Order Amount') }}</h5>
                                    </div>
                                    <div class="light-dark-bg px-4 p-4 mt-3 text-gray">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="d-flex justify-content-between border-bottom-1 py-2">
                                                    <div>
                                                        <p class="fs-16 fw-500 mb-0">{{ translate('Subtotal') }}</p>
                                                    </div>
                                                    <div class="fs-16">
                                                        <p class="fs-16 fw-500 mb-0">
                                                            {{ single_price($order->orderDetails->sum('price')) }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom-1 py-2">
                                                    <div>
                                                        <p class="fs-16 fw-500 mb-0">{{ translate('Shipping') }}</p>
                                                    </div>
                                                    <div class="fs-16">
                                                        <p class="fs-16 fw-500 mb-0">
                                                            {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom-1 py-2">
                                                    <div>
                                                        <p class="fs-16 fw-500 mb-0">{{ translate('Tax') }}</p>
                                                    </div>
                                                    <div class="fs-16">
                                                        <p class="fs-16 fw-500 mb-0">
                                                            {{ single_price($order->orderDetails->sum('tax')) }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom-1 py-2">
                                                    <div>
                                                        <p class="fs-16 fw-500 mb-0">{{ translate('Coupon') }}</p>
                                                    </div>
                                                    <div class="fs-16">
                                                        <p class="fs-16 fw-500 mb-0">
                                                            {{ single_price($order->coupon_discount) }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between border-bottom-0 py-2">
                                                    <div>
                                                        <h5 class="fw-700">{{ translate('Total') }}</h5>
                                                    </div>
                                                    <div class="fs-16">
                                                        <p class="fs-16 fw-700 mb-0">
                                                            {{ single_price($order->grand_total) }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                @if ($order->manual_payment && $order->manual_payment_data == null)
                                    <button onclick="show_make_payment_modal({{ $order->id }})"
                                        class="btn btn-block btn-primary">{{ translate('Make Payment') }}</button>
                                @endif
                            </div>


                            @php
                                $pickup_products = $order->orderDetails()->where('shipping_type', 'like', 'pickup_point')->get();
                            @endphp
                            {{-- Start Pickup --}}
                            @if ($pickup_products->count())
                            <div class="col-md-12">
                                <div class="card rounded-0 shadow-none mt-4 mb-4">
                                    <div class="card-header border-bottom-0">
                                        <h5 class="fs-16 fw-700 text-dark mb-0">{{ translate('Pickup Details') }}</h5>
                                    </div>
                                    <div class="light-dark-bg px-4 p-4 mt-3 text-gray">
                                        <div class="table-responsive">
                                            <table class="aiz-table table">
                                                <thead class="text-gray fs-12">
                                                    <tr>
                                                        <th class="pl-0">#</th>
                                                        <th width="30%">{{ translate('Product') }}</th>
                                                        <th data-breakpoints="md">{{ translate('Date/Time') }}</th>
                                                        <th>{{ translate('Location') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="fs-14">
                                                    @foreach ($pickup_products as $key => $orderDetail)
                                                        <tr>
                                                            <td class="pl-0">{{ sprintf('%02d', $key + 1) }}</td>
                                                            <td class="pl-0">{{ $orderDetail->product?->name }}</td>
                                                            <td> {{ $orderDetail->product?->pickup_days}} {{ $orderDetail->product?->pickup_time}} </td>
                                                            <td> {{ $orderDetail->product?->pickup_address }} </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <small>Note: Further details will be provided.</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                            {{-- End Pickup --}}
                        </div>

                    </div>
                </div>
            </div>
            <div>
    </section>


    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="payment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div id="payment_modal_body">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function show_make_payment_modal(order_id) {
            $.post('{{ route('checkout.make_payment') }}', {
                _token: '{{ csrf_token() }}',
                order_id: order_id
            }, function(data) {
                $('#payment_modal_body').html(data);
                $('#payment_modal').modal('show');
                $('input[name=order_id]').val(order_id);
            });
        }

        function product_review(product_id) {
            $.post('{{ route('product_review_modal') }}', {
                _token: '{{ @csrf_token() }}',
                product_id: product_id
            }, function(data) {
                $('#product-review-modal-content').html(data);
                $('#product-review-modal').modal('show', {
                    backdrop: 'static'
                });
                AIZ.extra.inputRating();
            });
        }
    </script>
@endsection
