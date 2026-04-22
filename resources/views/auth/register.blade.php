@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/login.css" rel="stylesheet">
@endpush
@section('content')
{{-- @include('frontend.xthome.partials.xt-header') --}}
@include('frontend.xthome.partials.xt-header')

<!-----Shop section----->
<section class="shop-section">
    <div class="auto-container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap d-md-flex login_child">
                    <div class="col-md-7">
                        <div class="text-center pt-5 login_child"><img src="{{ uploaded_asset(get_setting('customer_register_page_image')) }}" alt="{{ translate('Customer Register Page Image') }}" class="img-fluid leftbg"></div>
                    </div>
                    <div class="col-md-5 align-self-center">
                        <div class="flex-wrap">
                            <div class="d-flex">
                                <div class="w-100">
                                    <p class="fw-bold text-center text-danger"></p>
                                    <h3 class="mb-4">{{ translate('Create an account')}}</h3>
                                </div>
                            </div>
                            <div class="signin-form">
                                <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-floating mb-4">
                                        <input id="register_name" type="text" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Full Name') }}" name="name" required><label for="register_name">{{ translate('Full Name') }} *</label>
                                        @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('name') }}</small>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-floating mb-4">
                                        <input id="register_email" type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" autocomplete="email" required><label for="register_email">{{ translate('Email') }} *</label>
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('email') }}</small>
                                        </span>
                                        @endif
                                    </div>



                                    <div class="form-floating mb-4">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password" id="password" class="form-control rounded-0 " placeholder="{{  translate('Password') }}" required autocomplete="new-password"><label for="password">{{ translate('Password') }} *</label>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('password') }}</small>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-floating mt-4">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password_confirmation" class="form-control rounded-0" id="password_confirmation" placeholder="{{  translate('Confirm Password') }}" required autocomplete="new-password"><label for="password_confirmation">{{ translate('Confirm Password') }} *</label>
                                        @if ($errors->has('confirm_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('confirm_password') }}</small>
                                        </span>
                                        @endif
                                    </div>


                                    <div class="form-floating mt-4">
                                        <input type="text" class="form-control rounded-0{{ $errors->has('govt_id') ? ' is-invalid' : '' }}" value="{{ old('govt_id') }}" placeholder="{{  translate('Govt ID') }}" name="govt_id" required><label for="govt_id">{{ translate('Drivers License Number') }} *</label>
                                        @if ($errors->has('govt_id'))
                                        <span class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('govt_id') }}</small>
                                        </span>
                                        @endif
                                    </div>

                                    <!-- <div class="form-floating mt-4">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="form-control input-group-text bg-soft-secondary font-weight-medium rounded-0">Browse</div>
                                            </div>
                                            <div class="form-control file-amount">Choose File Drivers License Photo</div>
                                            <input type="hidden" name="id_image" value="" class="selected-files"  accept=".jpg,.jpeg,.png,.gif" >
                                        </div>
                                    </div> -->
                                    <div class="input-group mt-4">
                                        <label class="form-control h-48 d-flex gap-2 items-center">
                                            <div class="input-group-text theme-btn-card rounded-0 d-flex items-center">Browse</div>
                                            <div id="file-name" >Choose File Drivers License Photo *</div>
                                            <input type="file" class="invisible form-control rounded-0 position-absolute"  accept=".jpg,.jpeg,.png,.gif" name="id_image" required onchange="updateFileName(this)">
                                        </label>
                                    </div>

                                    <!-- <div class="form-group">
                                        <div class="form-floating mt-4">
                                            <input type="file" class="form-control rounded-0" accept=".jpg,.jpeg,.png,.gif" name="id_image" required>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <div class="form-check text-start my-3 d-flex justify-content-between">
                                            <div><input class="form-check-input" type="checkbox" id="flexCheckDefault1" name="checkbox_example_1" required><label class="form-check-label" for="flexCheckDefault1">{{ translate('By signing up you agree to our ')}} <a href="{{ route('terms') }}" class="form-check-label">{{ translate('terms and conditions.') }}</a></label></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Create Account') }}</button></div>
                                    </div>
                                </form>
                            </div>
                            @if(get_setting('google_recaptcha') == 1)
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                            </div>
                            @if ($errors->has('g-recaptcha-response'))
                            <span class="invalid-feedback" role="alert" style="display: block;">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                            @endif
                            @endif
                            <!-- Social Login -->

                            <p class="text-center pt-4 login-small"> {{ translate('Already have an account?')}} <a href="{{ route('user.login') }}">{{ translate('Log In')}}</a></p>
                            <!-- <div class="mt-3 mr-4 mr-md-0">
                            <a href="{{ url()->previous() }}">
                                <i class="las la-arrow-left fs-20 mr-1"></i>
                                {{ translate('Back to Previous Page')}}
                            </a>
                    </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('script')
@if(get_setting('google_recaptcha') == 1)
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script type="text/javascript">
    @if(get_setting('google_recaptcha') == 1)
    // making the CAPTCHA  a required field for form submission
    $(document).ready(function() {
        $("#reg-form").on("submit", function(evt) {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                //reCaptcha not verified
                alert("please verify you are human!");
                evt.preventDefault();
                return false;
            }
            //captcha verified
            //do the rest of your validations here
            $("#reg-form").submit();
        });
    });
    @endif
</script>


@endsection
