@extends('frontend.layouts.xt-app')

@section('content')
    <section class="shop-section">
        <div class="auto-container">
            <div class="row">
                <div class="col-md-6 col-lg-5 d-none d-md-block">
                    <div class="text-center pt-5 login_child"><img
                            src="{{ uploaded_asset(get_setting('customer_login_page_image')) }}"
                            class="img-fluid leftbg" alt="{{ translate('Customer Login Page Image') }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></div>
                </div>
                <div class="col-md-6 offset-lg-1 align-self-center">
                    <div class="flex-wrap">
                        <div class="d-flex">
                            <div class="w-100">
                                <p class="fw-bold text-center text-danger"></p>

                                <h3 class="mb-4">{{ translate('Login to your account') }}</h3>
                            </div>
                        </div>
                        <div class="signin-form">
                            <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                @csrf
                                <div class="form-floating mb-4">
                                    <div class="form-floating mb-4"><input type="email" name="email"
                                            class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                            id="email" placeholder="Email Address" ><label for="email">{{__('Email Address')}} <span class="text-danger">*</span> </label>
                                    @if ($errors->has('email'))
                                        <p class="text-danger" role="alert">
                                            <small>{!! $errors->first('email') !!}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="form-floating mb-0">
                                    <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>

                                    <input type="password" name="password"
                                            class="form-control" id="password" placeholder="Password" oninput="this.value = this.value.replace(/\s/g, '')" autocomplete="off" ><label
                                            for="password">{{__('Password')}} <span class="text-danger">*</span> </label>

                                    @if ($errors->has('password'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('password') }}</small>
                                        </p>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="form-check text-start my-3 d-flex justify-content-between">
                                        <div><input class="form-check-input" type="checkbox"
                                                id="flexCheckDefault1"><label class="form-check-label"
                                                for="flexCheckDefault1">{{ translate('Remember Me') }}</label></div>
                                        <div><a href="javascript:void(0);" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal"
                                                id="show_forget_modal"
                                                class="form-check-label forgetlink">{{ translate('Forgot password?') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{__('Sign In')}}</button></div>
                                </div>
                            </form>
                        </div>
                        <!-- <p class="text-center pt-4 login-small">{{ translate('Dont have an account?') }}
                                <a href="{{ route('user.registration') }}">{{ translate('Register Now') }}</a></p> -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('auth.xt-auth.partials.forgot-password-model')

@endsection

{{-- @section('scriptjs')
<script type="text/javascript">
$(document).ready(function(){
$("#show_forget_modal").click(function(){
$("#div1").remove();
});
});
</script>
@endsection --}}
