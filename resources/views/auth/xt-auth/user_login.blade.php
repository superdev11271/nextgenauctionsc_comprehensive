@extends('frontend.layouts.xt-app')

@section('content')

<section class="shop-section">
    <div class="auto-container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap d-md-flex login_child">
                    <div class="col-md-7 d-none d-md-block">
                        <div class="text-center pt-5 login_child"><img src="{{ uploaded_asset(get_setting('customer_login_page_image')) }}" class="img-fluid leftbg" alt="{{ translate('Customer Login Page Image') }}"></div>
                    </div>
                    <div class="col-md-5 align-self-center">
                        <div class="flex-wrap">
                            <div class="d-flex">
                                <div class="w-100">
                                    <p class="fw-bold text-center text-danger"></p>

                                    <h3 class="mb-4">{{ translate('Login to your account')}}</h3>
                                </div>
                            </div>
                            <div class="signin-form">
                                <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="form-floating mb-4">
                                        <div class="form-floating mb-4"><input type="email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"  placeholder="{{  translate('johndoe@example.com') }}"><label for="email">{{  translate('Email') }}</label></div>
                                        @if ($errors->has('email'))
                                        <p class="invalid-feedback" role="alert">
                                            <small> {!! $errors->first('email') !!}</small>
                                        </p>
                                        @endif
                                    </div>
                                    <div class="form-floating mb-0">
                                        <div class="form-floating mb-0"><input type="password" name="password" class="form-control" id="name" placeholder="{{  translate('Password') }}"><label for="name">{{  translate('Password') }} *</label></div>
                                        <i class="password-toggle las la-2x la-eye"></i>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check text-start my-3 d-flex justify-content-between">
                                            <div><input class="form-check-input" type="checkbox" id="customerflexCheckDefault"><label class="form-check-label" for="customerflexCheckDefault">{{ translate('Remember Me') }}</label></div>
                                            <div>
                                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModal" class="form-check-label forgetlink">{{ translate('Forgot password?')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{  translate('Login') }}</button></div>
                                    </div>
                                </form>
                            </div>
                            <!-- Social Login -->

                            <!-- <p class="text-center pt-4 login-small">{{ translate('Dont have an account?')}} <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a></p> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('auth.xt-auth.partials.forgot-password-model')

@endsection
