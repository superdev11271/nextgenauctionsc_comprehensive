@extends('seller.layouts.app')
@section('panel_content')

    <div class="aiz-titlebar mt-2 mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Purchase Package List') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('All Purchase Package') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th width="30%">{{ translate('Package')}}</th>
                        <th data-breakpoints="md">{{ translate('Package Price')}}</th>
                        <th data-breakpoints="md">{{ translate('Payment Type')}}</th>
                        <th data-breakpoints="md">{{ translate('Bought Package')}}</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($seller_packages_payment as $key => $payment)
                        <tr>
                            <td>{{ ($key+1) + ($seller_packages_payment->currentPage() - 1) * $seller_packages_payment->perPage() }}</td>
                            <td>{{ $payment->seller_package->name ?? translate('Package Unavailable') }}</td>
                            <td>{{ $payment->seller_package->amount ?? translate('Package Unavailable') }}</td>
                            <td>
                                @if($payment->offline_payment == 1)
                                    {{ translate('Offline Payment') }}
                                @else
                                    {{ translate('Online Payment') }}
                                @endif
                            </td>
                            @php
                                $package_details = json_decode($payment->package_details);
                            @endphp
                            <td>
                               @if($package_details)
                                <a class="fw-600 mb-3 text-primary">Package: </a>
                                <h6 class="text-primary">
                                    {{$package_details->name ?? ''}}
                                </h6>
                                <p class="mb-1 text-muted">Product Upload Limit:
                                    {{$package_details->product_upload_limit ?? ''}} Times
                                </p>
                                <p class="text-muted">Buy Date :
                                    {{\Carbon\Carbon::parse($package_details->created_at ?? '')->format('d-m-y')}}
                                </p>
                                <p class="text-muted">Duration:
                                    {{$package_details->duration ?? ''}} Days
                                </p>
                                <p class="text-muted">Expired:
                                    @if(isset($package_details->created_at))
                                        {{\Carbon\Carbon::parse($package_details->created_at ?? '')->addDays($package_details->duration ?? 0)->format('d-m-y')}}
                                    @else   
                                     N/A
                                    @endif
                                </p>
                               @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $seller_packages_payment->links() }}
          	</div>
        </div>
    </div>

@endsection
