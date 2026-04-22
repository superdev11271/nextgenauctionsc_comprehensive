@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Product Revenue')}}</h1>
		</div>
        {{-- @can('add_staff')
            <div class="col-md-6 text-md-right">
                <a href="{{ route('staffs.create') }}" class="btn btn-circle btn-info">
                    <span>{{translate('Add New Staffs')}}</span>
                </a>
            </div>
        @endcan --}}
	</div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('All Product Revenue')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th data-breakpoints="lg">{{translate('Customer')}}</th>
                    <th data-breakpoints="lg">{{translate('Product')}}</th>
                    <th data-breakpoints="lg">{{translate('Net Auction Amount')}}</th>
                    <th data-breakpoints="lg">{{translate('Admin Commission')}}</th>
                    <th data-breakpoints="lg">{{translate('Seller Commission')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($revenues as $key => $revenue)
                        <tr>
                            <td>{{ ($key + 1) + ($revenues->currentPage() - 1) * $revenues->perPage() }}</td>
                            <td>
                                <strong>{{translate('Customer Name')}}:</strong> {{ $revenue->name }} </br>
                                <strong>{{translate('Email')}}:</strong> {{ $revenue->email }}  </br>
                                <strong>{{translate('Phone')}}:</strong> {{ $revenue->phone }} </br>
                            </td>
                            <td>{{$revenue->meta_title}}</td>
                            <td>{{$revenue->price}}</td>
                            <td>{{$revenue->admin_commission}}</td>
                            <td>{{$revenue->seller_earning}}</td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $revenues->links() }}
        </div>
    </div>
</div>

@endsection


