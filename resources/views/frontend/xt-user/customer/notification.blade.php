@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush
@section('content')
<!-- account details -->
<section class="shop-section account-details pt-5">
    <div class="auto-container">
        <div class="row">
            @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">
                    <div class="card mb-5">
                        <div class="card-header py-3">
                            <h5 class="m-0">All Notifications</h5>
                        </div>
                        <div class="card-body table-responsive">
                            @if(auth()->user()->user_type == 'customer' && auth()->user()->shop)
                                <x-notification :notifications="$notifications" is_linkable="false"/>
                                <div class="aiz-pagination aiz-pagination-center mt-4">
                                {{  $notifications->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
                                </div>
                            @else
                                @forelse($notifications as $notification)
                                        @if($notification->type == 'App\Notifications\OrderNotification')
                                            <li class="list-group-item d-flex justify-content-between align-items- py-3 px-0">
                                                <div class="media text-inherit">
                                                    <div class="media-body">
                                                        <p class="mb-1 text-truncate-2">
                                                            {{translate('Your Order: ')}}
                                                            <a href="{{route('purchase_history.details', encrypt($notification->data['order_id']))}}">
                                                                {{$notification->data['order_code']}}
                                                            </a>
                                                            {{translate(' has been '. ucfirst(str_replace('_', ' ', $notification->data['status'])))}}
                                                        </p>
                                                        <small class="text-muted">
                                                            {{ date("F j Y, g:i a", strtotime($notification->created_at)) }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                @empty  
                                    <div class="rounded bg-dark text-center p-3">
                                        <div class="fs-18 fw-600">
                                            {{ translate('No notification found') }}
                                        </div>
                                    </div>
                                @endforelse
                                <div class="aiz-pagination aiz-pagination-center mt-4">
                                {{  $notifications->appends(request()->input())->links('frontend.xthome.partials.custom_pagination')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>
</section>
@endsection
