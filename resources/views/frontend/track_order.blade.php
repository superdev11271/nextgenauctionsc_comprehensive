@extends('frontend.layouts.xt-app')

@section('content')
    <section class="mb-5 trak_order">
        <div class="container text-left">
            <div class="row">
                <div class="mt-4 col-xxl-12 col-xl-12 col-lg-12 mx-auto">
                    <div class="border-top-section">
                        <div class="fs-18 fw-600 pb-4">
                            {{ translate('Check Your Order Status')}}
                        </div>
                        <form class="form-default" role="form" action="{{ route('orders.track') }}" method="GET" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-floating mb-4 w-100">
                                        <input type="text" required class="form-control" value="{{ old('order_code') }}" placeholder="{{ translate('Order Code')}}" name="order_code" id="order_code" autocomplete="off"><label for="order_code">{{  translate('order_code') }}</label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Track Order')}}</button></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @isset($order)
                <div class="rounded-0 mt-5 border-top-section light-dark-bg p-2">
                    <div class="fs-18 fw-600 p-3">
                        {{ translate('Order Summary')}}
                    </div>
                    <div class="p-3">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="border-top-section table-responsive h-100">
                                    <table class="table">
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Order Code')}}</td>
                                            <td>{{ $order->code ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Customer')}}:</td>
                                            <td>{{ json_decode($order->shipping_address)->name?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Email')}}:</td>
                                            @if ($order->user_id != null)
                                                <td>{{ $order->user->email ?? 'Not Found' }}</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Shipping address')}}:</td>
                                            <td>{{ json_decode($order->shipping_address)->address?? 'N/A' }}, {{ json_decode($order->shipping_address)->city ?? 'N/A' }}, {{ json_decode($order->shipping_address)->country ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('billing address')}}:</td>
                                            <td>{{ json_decode($order->billing_address)->address?? 'N/A' }}, {{ json_decode($order->billing_address)->city ?? 'N/A' }}, {{ json_decode($order->billing_address)->country ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="border-top-section table-responsive h-100">
                                    <table class="table">
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Order date')}}:</td>
                                        <td>{{ date('d-m-Y H:i A', $order->date) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Total order amount')}}:</td>
                                        <td>{{ single_price($order->orderDetails->sum('price') + $order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Shipping method')}}:</td>
                                        <td>
                                            @if ($order->shipping_type == 'home_delivery')
                                                {{ translate('Home Delivery') }}
                                            @elseif ($order->shipping_type == 'pickup_point')
                                                {{ translate('Pick Up') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Payment method')}}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="w-50 fw-600">{{ translate('Delivery Status')}}:</td>
                                        <td>{{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}</td>
                                    </tr>
                                    @if ($order->tracking_code)
                                        <tr>
                                            <td class="w-50 fw-600">{{ translate('Tracking code')}}:</td>
                                            <td>{{ $order->tracking_code ?? 'N/A' }}</td>
                                        </tr>
                                    @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($order->orderDetails as $key => $orderDetail)
                    @php
                        $status = $order->delivery_status;
                    @endphp
                    @if($orderDetail->product != null)
                    <div class="rounded-0 mt-5 border-top-section light-dark-bg p-2">
                        <div class="row">

                            <div class="p-3">
                                <div class="border-top-section table-responsive ">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="border-0">{{ translate('Product Name')}}</th>
                                                <th class="border-0">{{ translate('Quantity')}}</th>
                                                <th class="border-0">{{ translate('Shipped By')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                            <td>{{ $orderDetail->product->getTranslation('name') }} @if($orderDetail->variation)({{ $orderDetail->variation }}) @endif</td>
                                                <td>{{ $orderDetail->quantity ?? 'N/A' }}</td>
                                                <td>{{ $orderDetail->product->user->name ?? 'Not Found!' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                      </div>
                    </div>
                    @else
                    <div class="rounded-0 mt-5 border-top-section light-dark-bg p-2">
                        <div class="row">

                            <div class="p-3">
                                <div class=" table-responsive ">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <td>{{ __('Product Not Found')}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                      </div>
                    </div>
                    @endif
                @endforeach

            @endisset
            <a class="theme-btn-two mt-2" href="{{url()->previous()}}">{{__('Back')}}</a>
        </div>
    </section>
@endsection
