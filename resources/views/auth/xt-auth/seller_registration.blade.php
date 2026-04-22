@extends('frontend.layouts.xt-app')

@section('content')

<section class="shop-section">
    <div class="auto-container">                                          
        <div class="row">
            <div class="col-md-12">
                <div class="wrap d-md-flex login_child">
                    <div class="col-md-7 d-none d-md-block">
                        <div class="text-center pt-5 login_child"><img src="{{ uploaded_asset(get_setting('seller_register_page_image')) }}" alt="{{ translate('Seller Register Page Image') }}" class="img-fluid leftbg"></div>
                    </div>
                        <div class="col-md-5 align-self-center">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="flex-wrap">
                            <div class="d-flex">
                               
                                <div class="w-100">
                                    <p class="fw-bold text-center text-danger"></p>
                                    <h1 class="mb-4">{{ translate('Register as a Seller')}}</h1>
                                </div>
                            </div>
                            <div class="signin-form">
                                <div class="fs-15 fw-600 pb-2">{{ translate('Personal Info')}}</div>
                                <form id="reg-form" class="form-default" role="form" action="{{ route('shops.store') }}" method="POST">
                                    @csrf
                                    <div class="form-floating mb-4">
                                        <input id="seller_reg_name" type="text" value="{{old('name')}}" class="form-control rounded-0{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name') }}" placeholder="{{  translate('Full Name') }}" name="name" autocomplete="name" required ><label for="seller_reg_name">{{ translate('Full Name') }}<span class="text-danger">*</span></label>
                                        @if ($errors->has('name'))
                                        <p class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('name') }}</small>
                                        </p>
                                        @endif
                                    </div>
                                    <div class="form-floating mb-4">
                                        <input id="seller_reg_email" type="email" value="{{old('email')}}" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" autocomplete="email" required ><label for="seller_reg_email">{{ translate('Email') }}<span class="text-danger">*</span></label>
                                        @if ($errors->has('email'))
                                        <p class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('email') }}</small>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="form-floating mb-0">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password"  oninput="this.value = this.value.replace(/\s/g, '')"
                                        id="password" class="form-control rounded-0 " placeholder="{{  translate('Password') }}" autocomplete="new-password"><label for="password">{{ translate('Password') }} <span class="text-danger">*</span></label>
                                        <div class="text-right mt-1">
                                            <span class="fs-12 fw-400">{{ translate('Password must be at least 8 characters long.') }}</span>
                                        </div>
                                        @if ($errors->has('password'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('password') }}</small>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="form-floating mt-4">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password_confirmation"  oninput="this.value = this.value.replace(/\s/g, '')"
                                        class="form-control rounded-0" id="password_confirmation" placeholder="{{  translate('Confirm Password') }}" autocomplete="new-password"><label for="password_confirmation">{{ translate('Confirm Password') }} <span class="text-danger">*</span></label>
                                        @if ($errors->has('confirm_password'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('confirm_password') }}</small>
                                        </p>
                                        @endif
                                    </div>
                                    <div class="form-floating mt-4">
                                        <input type="text" id="shop_name" value="{{old('shop_name')}}" class="form-control rounded-0{{ $errors->has('shop_name') ? ' is-invalid' : '' }}" value="{{ old('shop_name') }}" placeholder="{{  translate('Shop Name') }}" name="shop_name" required> <label for="shop_name">{{ translate('Business Name') }} <span class="text-danger">*</span></label>
                                        @if ($errors->has('shop_name'))
                                        <p class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('shop_name') }}</small>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="form-floating mt-4">
                                        <input type="text" id="address" class="form-control rounded-0{{ $errors->has('address') ? ' is-invalid' : '' }}" value="{{ old('address') }}" placeholder="{{  translate('Address') }}" name="address" required><label for="address" required >{{ translate('Address') }} <span class="text-danger">*</span></label>
                                        @if ($errors->has('address'))
                                        <p class="invalid-feedback" role="alert">
                                            <small>{{ $errors->first('address') }}</small>
                                        </p>
                                        @endif
                                    </div>

                                    <div class="col mt-4">
                                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Register Your Business') }}</button></div>
                                    </div>
                                </form>
                            </div>
                            
                            <p class="text-center pt-4 login-small"> {{ translate('Already have an account?')}} <a href="{{ route('seller.login') }}">{{ translate('Log In')}}</a></p>
                            <!-- <div class="mt-3 mr-4 mr-md-0">
                                <a href="{{ url()->previous() }}" class="ml-auto fs-14 fw-700 d-flex align-items-center text-primary" style="max-width: fit-content;">
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