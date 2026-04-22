@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{ static_asset('xt-assets') }}/css/login.css" rel="stylesheet">
@endpush
@section('content')

<!-----Shop section----->
<section class="shop-section">
    <div class="auto-container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap d-md-flex login_child">
                    <div class="col-md-12">
                        <div class="flex-wrap">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <div class="d-flex">
                                <div class="w-100">
                                    <h3 class="mb-4">{{ translate('Create an account')}}</h3>
                                </div>
                            </div>

                            <div class="signin-form">
                                <form id="reg-form" class="form-default" role="form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <!-- SECTION 1: PERSONAL INFORMATION -->
                                    <div class="form-section mb-5">
                                        <h5 class="section-title mb-4">{{ translate('Personal Information') }}</h5>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <input id="first_name" type="text" class="form-control rounded-0 {{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                                        placeholder="First Name" name="first_name" value="{{ old('first_name') }}"
                                                        autocomplete="given-name"
                                                        oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" required>
                                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                                    @if ($errors->has('first_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <small>{{ $errors->first('first_name') }}</small>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <input id="last_name" type="text" class="form-control rounded-0 {{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                                        placeholder="Last Name" name="last_name" value="{{ old('last_name') }}"
                                                        autocomplete="family-name"
                                                        oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" required>
                                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                                    @if ($errors->has('last_name'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <small>{{ $errors->first('last_name') }}</small>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <input id="user_registration_email" type="email" class="form-control rounded-0 {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                        placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="email" required>
                                                    <label for="user_registration_email">Email <span class="text-danger">*</span></label>
                                                    @if ($errors->has('email'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <small>{{ $errors->first('email') }}</small>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <input id="phone" type="tel" class="form-control rounded-0 {{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                        placeholder="Mobile Number" name="phone" value="{{ old('phone') }}"
                                                        autocomplete="tel"
                                                        oninput="this.value=this.value.replace(/[^0-9+]/g,'')" required>
                                                    <label for="phone">Mobile Number <span class="text-danger">*</span></label>
                                                    @if ($errors->has('phone'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <small>{{ $errors->first('phone') }}</small>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- SECTION 2: SECURITY INFORMATION -->
                                    <div class="form-section mb-5">
                                        <h5 class="section-title mb-4">{{ translate('Security Information') }}</h5>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i></span>
                                                    <input type="password" name="password" id="password" oninput="this.value = this.value.replace(/\s/g, '')"
                                                        class="form-control rounded-0" placeholder="Password" required autocomplete="new-password">
                                                    <label for="password">Password <span class="text-danger">*</span></label>
                                                    <small>* Password must include at least one lowercase, uppercase, number, and special character.</small>
                                                    @if ($errors->has('password'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <small>{{ $errors->first('password') }}</small>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-4">
                                                    <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i></span>
                                                    <input type="password" name="password_confirmation" oninput="this.value = this.value.replace(/\s/g, '')"
                                                        class="form-control rounded-0" id="password_confirmation" placeholder="Confirm Password" required autocomplete="new-password">
                                                    <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Terms and Submit -->
                                    <div class="form-group">
                                        <div class="form-check text-start my-3">
                                            <input class="form-check-input" type="checkbox" id="terms-checkbox" name="checkbox_example_1" required>
                                            <label class="form-check-label" for="terms-checkbox">
                                                I agree to the <a href="{{ route('staticPages', 'terms') }}" target="_blank">Terms and Conditions</a> <span class="text-danger">*</span>
                                            </label>
                                        </div>
                                    </div>

                                    @if(get_setting('google_recaptcha') == 1)
                                    <div class="form-group mb-4">
                                        <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                                        @if ($errors->has('g-recaptcha-response'))
                                        <span class="invalid-feedback" role="alert" style="display: block;">
                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    @endif

                                    <div class="form-group">
                                        <button type="submit" class="theme-btn-two w-100 py-3">Create Account</button>
                                    </div>
                                </form>

                                <p class="text-center pt-4 login-small">
                                    {{ translate('Already have an account?')}}
                                    <a href="{{ route('user.login') }}">{{ translate('Log In')}}</a>
                                </p>
                            </div>
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
