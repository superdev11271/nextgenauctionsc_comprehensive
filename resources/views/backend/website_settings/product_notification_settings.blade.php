@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Product Notification Settings') }}</h1>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-8 mx-auto">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">{{ translate('Product Notification') }}</h6>
			</div>
			<div class="card-body">
				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
					@csrf
				
					<div class="border-top pt-3">
						<div class="form-group row">
							<label class="col-md-3 col-form-label" >{{translate('Time Interval Type')}} <i class="las la-info text-primary" data-toggle="tooltip" title="This will set when the alert is sent based on the selected time period."></i></label>
							<div class="col-md-6">
								<div class="form-group">
									<!-- Dropdown for time type selection -->
									<input type="hidden" name="types[]" value="auction_time_type">
									<select class="form-control mt-2" name="auction_time_type">
										<option value="days" {{ get_setting('auction_time_type') == 'days' ? 'selected' : '' }}>{{ translate('Days') }}</option>
										<option value="hours" {{ get_setting('auction_time_type') == 'hours' ? 'selected' : '' }}>{{ translate('Hours') }}</option>
										<option value="minutes" {{ get_setting('auction_time_type') == 'minutes' ? 'selected' : '' }}>{{ translate('Minutes') }}</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="border-top pt-3">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label" data-toggle="tooltip" title="" >{{translate('Auction Start Alerts')}} <i class="las la-info text-primary" data-toggle="tooltip" title="Specify time frames (e.g., 1, 2, ...) based on the selected time type. This determines when alerts will be sent before the auction starts."></i></label>
							<div class="col-md-6">
								<div class="form-group">
									<input type="hidden" name="types[]" value="upcoming_auction_days">
									<input type="text" oninput="this.value = this.value.replace(/[^0-9,]/g, '')" class="form-control" placeholder="{{ translate('1,2 ...') }}" name="upcoming_auction_days" value="{{ get_setting('upcoming_auction_days') }}">
								</div>
							</div>
						</div>
                    </div>
					

                    <div class="border-top pt-3">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label" data-toggle="tooltip" title="">{{translate('Auction End Alerts')}} <i class="las la-info text-primary" data-toggle="tooltip" title="Set time frames (e.g., 1, 2, ...) for when alerts should trigger before the auction ends, based on the selected time type."></i> </label>
							<div class="col-md-6">
								<div class="form-group">
									<input type="hidden" name="types[]" value="live_auction_days">
									<input type="text" oninput="this.value = this.value.replace(/[^0-9,]/g, '')" class="form-control" placeholder="{{ translate('1,2 ...') }}" name="live_auction_days" value="{{ get_setting('live_auction_days') }}">
								</div>
							</div>
						</div>
                    </div>


					<div class="border-top pt-3">
                        <div class="form-group row">
							<label class="col-md-3 col-from-label">{{translate('Marketplace End Alerts ')}} <i class="las la-info text-primary" data-toggle="tooltip" title="Set time frames (e.g., 1, 2, ...) for when alerts should trigger before the marketplace product expires, based on the selected time type."></i></label>
							<div class="col-md-6">
								<div class="form-group">
									<input type="hidden" name="types[]" value="marketplace_product_days">
									<input type="text" oninput="this.value = this.value.replace(/[^0-9,]/g, '')" class="form-control" placeholder="{{ translate('1,2 ...') }}" name="marketplace_product_days" value="{{ get_setting('marketplace_product_days') }}" >
								</div>
							</div>
						</div>
                    </div>	
					<span class="text-danger"> Note: If you select the 'day' type, the field must contain values like 1, 2, etc., meaning the alerts will be sent in days. If you select 'hours,' the alerts will be sent in hours. Similarly, if you choose 'minutes,' the alerts will be sent accordingly.</span> 
					<!-- Update Button -->
					<div class="mt-4 text-right">
						<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
