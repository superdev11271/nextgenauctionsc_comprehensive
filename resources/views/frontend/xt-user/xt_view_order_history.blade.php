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
                        <div class="card-header py-3">
                            <h5 class="m-0">Orders</h5>
                        </div>
                        @if(count($orders)>0)
                        <div class="card-body table-responsive">
                            <table class="shopping-cart table table-responsive-md  text-nowrap">
                                <thead>
                                    <tr>
                                        <th>{{ translate('Code') }}</th>
                                        <th>{{ translate('Date') }}</th>
                                        <th>{{ translate('Amount') }}</th>
                                        <th>{{ translate('Delivery Status') }}</th>
                                        <th>{{ translate('Payment Status') }}</th>
                                        <th>{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $key => $order)
                                        @if (count($order->orderDetails) > 0)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('purchase_history.details', encrypt($order->id)) }}">{{ $order->code }}</a>
                                                </td>
                                                <td>{{ date('d-m-Y', $order->date) }}</td>
                                                <td>{{ single_price($order->grand_total) }}</td>
                                                <td>
                                                <a class="btn-sm mx-1"
                                                        href="{{ route('orders.track', ['order_code' => $order->code]) }}"
                                                        title="{{ translate('Track Order') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck" viewBox="0 0 16 16">
  <path d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5zm1.294 7.456A2 2 0 0 1 4.732 11h5.536a2 2 0 0 1 .732-.732V3.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .294.456M12 10a2 2 0 0 1 1.732 1h.768a.5.5 0 0 0 .5-.5V8.35a.5.5 0 0 0-.11-.312l-1.48-1.85A.5.5 0 0 0 13.02 6H12zm-9 1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m9 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2"/>
</svg>
                                                    </a>
                                                    {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                                    @if ($order->delivery_viewed == 0)
                                                        <span class="ml-2" style="color:green"><strong>*</strong></span>
                                                    @endif

                                                </td>
                                                <td>
                                                    @if ($order->payment_status == 'paid')
                                                        <span class="badge bg-success">{{ translate('Paid') }}</span>
                                                    @else
                                                        <span class="badge bg-info">{{ translate('Unpaid') }}</span>
                                                    @endif
                                                    @if ($order->payment_status_viewed == 0)
                                                        <span class="ml-2 text-success"><strong>*</strong></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <!-- Re-order -->
                                                    <a class="btn btn-dark btn-sm mx-1"
                                                        href="{{ route('re_order', encrypt($order->id)) }}">
                                                        {{ translate('Reorder') }}
                                                    </a>
                                                    <!-- Cancel -->
                                                    @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                                        <a href="javascript:void(0)" class="btn btn-dark btn-sm  mx-1 confirm-delete" data-href="{{route('purchase_history.destroy', encrypt($order->id))}}" title="{{ translate('Cancel') }}">
                                                         <svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">
                                                             <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>
                                                         </svg>
                                                     </a>
                                                    @endif
                                                    <!-- Details -->
                                                    <a href="{{ route('purchase_history.details', encrypt($order->id)) }}"
                                                        class="btn btn-dark btn-sm mx-1"
                                                        title="{{ translate('Order Details') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                            height="10" viewBox="0 0 12 10">
                                                            <g id="Group_24807" data-name="Group 24807"
                                                                transform="translate(-1339 -422)">
                                                                <rect id="Rectangle_18658" data-name="Rectangle 18658"
                                                                    width="12" height="1"
                                                                    transform="translate(1339 422)" fill="#3490f3" />
                                                                <rect id="Rectangle_18659" data-name="Rectangle 18659"
                                                                    width="12" height="1"
                                                                    transform="translate(1339 425)" fill="#3490f3" />
                                                                <rect id="Rectangle_18660" data-name="Rectangle 18660"
                                                                    width="12" height="1"
                                                                    transform="translate(1339 428)" fill="#3490f3" />
                                                                <rect id="Rectangle_18661" data-name="Rectangle 18661"
                                                                    width="12" height="1"
                                                                    transform="translate(1339 431)" fill="#3490f3" />
                                                            </g>
                                                        </svg>
                                                    </a>
                                                    <!-- Invoice -->
                                                    <a class="btn btn-dark btn-sm mx-1"
                                                        href="{{ route('invoice.download', encrypt($order->id)) }}"
                                                        title="{{ translate('Download Invoice') }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                            height="12.001" viewBox="0 0 12 12.001">
                                                            <g id="Group_24807" data-name="Group 24807"
                                                                transform="translate(-1341 -424.999)">
                                                                <path id="Union_17" data-name="Union 17"
                                                                    d="M13936.389,851.5l.707-.707,2.355,2.355V846h1v7.1l2.306-2.306.707.707-3.538,3.538Z"
                                                                    transform="translate(-12592.95 -421)" fill="#f3af3d" />
                                                                <rect id="Rectangle_18661" data-name="Rectangle 18661"
                                                                    width="12" height="1"
                                                                    transform="translate(1341 436)" fill="#f3af3d" />
                                                            </g>
                                                        </svg>
                                                    </a>


                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="aiz-pagination aiz-pagination-center mt-4">
                              {{  $orders->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
                           </div>

                        </div>
                     @else
                     <div class="col-xl-12 col-lg-12 col-md-12 py-3">
                        <div class="rounded bg-dark text-center p-3">
                            <div class="fs-18 fw-600">
                                {{ translate('Your Orders is empty') }}
                            </div>
                        </div>
                     </div>
                     @endif
                    </div>
                </div>
            </div>
    </section>

    @include('modals.delete_modal')

@endsection




