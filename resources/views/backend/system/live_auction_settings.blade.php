@extends('backend.layouts.app')

@section('content')
    <div class="row">

        {{-- Paypal --}}
        @if ($errors->any())
           <div class="col-md-12">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                </div>
           </div>
        @endif
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">{{ translate('Auction Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('business_settings.bulk_update') }}" method="POST">
                        @csrf



                        <div class="form-group row">
							<label class="col-md-4 col-from-label mb-md-0">{{ translate('Pusher ON/OFF') }}
                                <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip"
                                title="Pusher is utilized to display live auction status updates without requiring a browser refresh."></i>

                            </label>
							<div class="col-md-8 d-flex">
								<div class="radio mar-btm mr-3 d-flex align-items-center">
									<input id="header_nav_menu_text_light" class="magic-radio" type="radio" name="business_settings[pusher_status]" value=1 @checked(get_setting('pusher_status') == 1)>
									<label for="header_nav_menu_text_light" class="mb-0 ml-2">On</label>
								</div>
								<div class="radio mar-btm mr-3 d-flex align-items-center">
									<input id="header_nav_menu_text_dark" class="magic-radio" type="radio" name="business_settings[pusher_status]" value=0 @checked(get_setting('pusher_status') == 0)>
									<label for="header_nav_menu_text_dark" class="mb-0 ml-2">Off</label>
								</div>
							</div>
						</div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Remaining Time to Extend Auction') }}
                                    <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip"
                                    title="Example: If there are 20 seconds remaining and a bid is placed, the auction time will be extended by the specified increment."></i>

                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="business_settings[auc_when_time_increment]"
                                value="{{get_setting('auc_when_time_increment') }}"
                                placeholder="{{ translate('Please enter the value in seconds.') }}" required>
                                <span class="text-danger">Example, 300 seconds equals 5 minutes.</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Extend Auction Time') }}
                                    <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip"
                                    title="Specify the amount of time to extend the auction when an extension is triggered."></i>
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="business_settings[auc_how_much_time_increment]"
                                    value="{{ get_setting('auc_how_much_time_increment') }}"
                                    placeholder="{{ translate('Please enter the value in seconds.') }}" required>
                                    <span class="text-danger">Example, 300 seconds equals 5 minutes.</span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{ translate('Set Auction Item End Gap') }}</label>
                                <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip"
                                    title="Ending Interval Between Auction Items: Define the time interval between the end of one auction item and the end of the next."></i>
                            </div>
                            <div class="col-md-8">
                                <input type="number" class="form-control" name="business_settings[auction_ending_interval]"
                                    value="{{ get_setting('auction_ending_interval') }}"
                                    placeholder="{{ translate('Please enter the value in seconds.') }}" required>
                                    <span class="text-danger">Example, 300 seconds equals 5 minutes.</span>
                            </div>
                        </div>

                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
