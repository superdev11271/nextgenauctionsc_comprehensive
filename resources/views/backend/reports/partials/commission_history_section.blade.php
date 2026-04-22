<form action="{{ route('commission-log.index') }}" method="GET">
    <div class="card-header row gutters-5">
        <div class="col text-center text-md-left mb-2 mb-lg-0">
            <h5 class="mb-md-0 h6">{{ translate('Commission History') }}</h5>
        </div>
        @if(Auth::user()->user_type != 'seller')
        <div class="col-md-3 ml-auto mb-2 mb-lg-0">
            <select id="demo-ease" class="form-control form-control-sm aiz-selectpicker mb-md-0" name="seller_id">
                <option value="">{{ translate('Choose Seller') }}</option>
                @foreach (App\Models\User::where('user_type', '=', 'seller')->get() as $key => $seller)
                    <option value="{{ $seller->id }}" @if($seller->id == $seller_id) selected @endif >
                        {{ $seller->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="col-md-3 mb-2 mb-lg-0">
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm aiz-date-range" id="search" name="date_range"@isset($date_range) value="{{ $date_range }}" @endisset placeholder="{{ translate('Date Range') }}">
            </div>
        </div>
        <div class="col-md-2">
            <button class="btn btn-md btn-primary" type="submit">
                {{ translate('Filter') }}
            </button>
        </div>
    </div>
</form>
<div class="card-body">

    <table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th data-breakpoints="lg">{{ translate('Order Code') }}</th>
                <th>{{ translate('Admin Commission') }}</th>
                <th>{{ translate('Seller Earning') }}</th>
                <th data-breakpoints="lg">{{ translate('Created At') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($commission_history as $key => $history)
            <tr>
                <td>{{ ($key+1) }}</td>
                <td>
                    @if(isset($history->order))
                        {{ $history->order->code }}
                    @else
                        <span class="badge badge-inline badge-danger">
                            {{ translate('Order Deleted') }}
                        </span>
                    @endif
                </td>
                <td>{{ $history->admin_commission }}</td>
                <td>{{ $history->seller_earning }}</td>
                <td>{{ $history->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="aiz-pagination mt-4">
        {{ $commission_history->appends(request()->input())->links() }}
    </div>
</div>