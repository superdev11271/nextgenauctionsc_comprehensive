@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
    	<div class="row align-items-center">
    		<div class="col">
    			<h1 class="h3">{{ translate('Website Footer') }}</h1>
    		</div>
    	</div>
    </div>

	<!-- Language -->
    <ul class="nav nav-tabs nav-fill language-bar">
        @foreach (get_all_active_language() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('website.footer', ['lang'=> $language->code] ) }}">
                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                    <span>{{$language->name}}</span>
                </a>
            </li>
        @endforeach
    </ul>



	<!-- Footer Bottom -->
    <div class="card">
    	<div class="card-header">
    		<h6 class="fw-600 mb-0">{{ translate('Footer Bottom') }}</h6>
    	</div>
        <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
           <div class="card-body">
				<!-- Copyright Widget -->
                <div class="card shadow-none bg-light">
                    <div class="card-header">
  						<h6 class="mb-0">{{ translate('Copyright Widget ') }}</h6>
  					</div>
                    <div class="card-body">
                        <div class="form-group">
                  			<label>{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
                  			<input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                  			<textarea class="aiz-text-editor form-control" name="frontend_copyright_text" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="150">
                                {!! get_setting('frontend_copyright_text',null,$lang) !!}
                            </textarea>
                  		</div>
                    </div>
                </div>

                <div class="card shadow-none bg-light">
                    <div class="card-header">
  						<h6 class="mb-0">{{ translate('Contact Address') }}</h6>
  					</div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Contact')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="contact_phone">
                                <input type="text" name="contact_phone" class="form-control" value="{{ get_setting('contact_phone') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label">{{translate('Eamil')}}</label>
                            <div class="col-sm-9">
                                <input type="hidden" name="types[]" value="contact_email">
                                <input type="text" name="contact_email" class="form-control" value="{{ get_setting('contact_email') }}">
                            </div>
                        </div>
                        <div class="form-group">
                  			<label>{{ translate('Contact Address') }} ({{ translate('Translatable') }})</label>
                  			<input type="hidden" name="types[][{{ $lang }}]" value="contact_address">
                  			<textarea class="aiz-text-editor form-control" name="contact_address" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]' placeholder="Type.." data-min-height="150">
                                {!! get_setting('contact_address',null,$lang) !!}
                            </textarea>
                  		</div>
                    </div>

                </div>

				<!-- Social Link Widget -->
                <div class="card shadow-none bg-light">
                  <div class="card-header">
						<h6 class="mb-0">{{ translate('Social Link Widget ') }}</h6>
					</div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label class="col-md-2 col-from-label">{{translate('Show Social Links?')}}</label>
                      <div class="col-md-9">
                        <label class="aiz-switch aiz-switch-success mb-0">
                          <input type="hidden" name="types[]" value="show_social_links">
                          <input type="checkbox" name="show_social_links" @if( get_setting('show_social_links') == 'on') checked @endif>
                          <span></span>
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Social Links') }}</label>
						<!-- Facebook Link -->
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="lab la-facebook-f"></i></span>
                            </div>
                            <input type="hidden" name="types[]" value="facebook_link">
                            <input type="text" class="form-control" placeholder="http://" name="facebook_link" value="{{ get_setting('facebook_link')}}">
                        </div>
						<!-- Twitter Link -->
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="lab la-twitter"></i></span>
                            </div>
                            <input type="hidden" name="types[]" value="twitter_link">
                            <input type="text" class="form-control" placeholder="http://" name="twitter_link" value="{{ get_setting('twitter_link')}}">
                        </div>
						<!-- Instagram Link -->
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="lab la-instagram"></i></span>
                            </div>
                            <input type="hidden" name="types[]" value="instagram_link">
                            <input type="text" class="form-control" placeholder="http://" name="instagram_link" value="{{ get_setting('instagram_link')}}">
                        </div>
						<!-- Youtube Link -->
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="lab la-youtube"></i></span>
                            </div>
                            <input type="hidden" name="types[]" value="youtube_link">
                            <input type="text" class="form-control" placeholder="http://" name="youtube_link" value="{{ get_setting('youtube_link')}}">
                        </div>
						<!-- Linkedin Link -->
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="lab la-linkedin-in"></i></span>
                            </div>
                            <input type="hidden" name="types[]" value="linkedin_link">
                            <input type="text" class="form-control" placeholder="http://" name="linkedin_link" value="{{ get_setting('linkedin_link')}}">
                        </div>
                    </div>
                  </div>
                </div>

				

				<!-- Payment Methods Widget -->
                <div class="card shadow-none bg-light">
                  	<div class="card-header">
						<h6 class="mb-0">{{ translate('Payment Methods Widget ') }}</h6>
					</div>
					<div class="card-body">
						<div class="form-group">
							<label>{{ translate('Payment Methods') }}</label>
							<div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
								<div class="input-group-prepend">
									<div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
								</div>
								<div class="form-control file-amount">{{ translate('Choose File') }}</div>
								<input type="hidden" name="types[]" value="payment_method_images">
								<input type="hidden" name="payment_method_images" class="selected-files" value="{{ get_setting('payment_method_images')}}">
							</div>
							<div class="file-preview box sm">
							</div>
						</div>
					</div>
                </div>

				<!-- Update Button -->
				<div class="mt-4 text-right">
					<button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success">{{ translate('Update') }}</button>
				</div>
            </div>
        </form>
	</div>
@endsection
