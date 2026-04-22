@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col-md-6">
			<h1 class="h3">{{translate('All Enquiry')}}</h1>
		</div>
       
	</div>
</div>



<div class="card">
    <div class="card-header">
        <h5 class="mb-0 h6">{{translate('Messages')}}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th data-breakpoints="lg" width="10%">#</th>
                    <th>{{translate('Name')}}</th>
                    <th data-breakpoints="lg">{{translate('Email')}}</th>
                    <th data-breakpoints="lg">{{translate('Messages')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $key => $message)
                    @if($message != null)
                        <tr>
                            <td>{{ ($key+1) + ($messages->currentPage() - 1)*$messages->perPage() }}</td>
                            <td>{{$message->name}}</td>
                            <td>{{$message->email}}</td>
                            <td>{{$message->message}}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
        <div class="aiz-pagination">
            {{ $messages->appends(request()->input())->links() }}
        </div>
    </div>
</div>

@endsection
