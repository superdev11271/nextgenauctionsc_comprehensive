@extends('backend.layouts.app')

@section('content')

	<div class="aiz-titlebar text-left mt-2 mb-3">
		<div class="row align-items-center">
			<div class="col">
				<h1 class="h3">{{ translate('Contact us') }}</h1>
			</div>
		</div>
	</div>

	<div class="row ">
		<div class="col-md-8 mx-auto">
			<div class="card">
				<div class="card-header">
					<h6 class="mb-0">{{ translate('Contact us Setting') }}</h6>
				</div>
				<div class="card-body">
					<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<!-- Header Logo -->
						<div class="form-group row">
							<label class="col-md-3 col-from-label">{{ translate('Contact Image') }}</label>
							<div class="col-md-8">
								<div class=" input-group " data-toggle="aizuploader" data-type="image">
									<div class="input-group-prepend">
										<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
									</div>
									<div class="form-control file-amount">{{ translate('Choose File') }}</div>
									<input type="hidden" name="types[]" value="contact_image">
									<input type="hidden" name="contact_image" class="selected-files" value="{{ get_setting('contact_image') }}">
								</div>
								<div class="file-preview box sm">
								</div>
							</div>
						</div>
						
						<div class="border-top pt-3">
							<!-- Help line number -->
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{translate('Mobile No.')}}</label>
								<div class="col-md-8">
									<div class="form-group">
										<input type="hidden" name="types[]" value="helpline_number">
										<input type="text" class="form-control" placeholder="{{ translate('Help line number') }}" name="helpline_number" value="{{ get_setting('helpline_number') }}">
									</div>
								</div>
							</div>
						</div>

						<div class="border-top pt-3">
							<!-- Email -->
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{translate('Email Address')}}</label>
								<div class="col-md-8">
									<div class="form-group">
										<input type="hidden" name="types[]" value="header_email">
										<input type="text" class="form-control" placeholder="{{ translate('Header Email') }}" name="header_email" value="{{ get_setting('header_email') }}">
									</div>
								</div>
							</div>
						</div>

						<div class="border-top pt-3">
							<!-- Address  -->
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{translate('Address')}}</label>
								<div class="col-md-8">
									<div class="form-group">
										<input type="hidden" name="types[]" value="contact_address">
										<textarea  class="form-control" placeholder="{{translate('Address')}}" name="contact_address">{{  get_setting('contact_address') }}</textarea>				
									</div>
								</div>
							</div>
						</div>

			
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
