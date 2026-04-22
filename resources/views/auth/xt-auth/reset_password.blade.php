@extends('frontend.layouts.xt-app')

@section('content')
    <!-- aiz-main-wrapper -->

    <section class="shop-section">
    <div class="auto-container">
        <div class="row">
            <div class="col-md-12">
                <div class="wrap d-md-flex login_child">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="col-md-7">
                        <div class="text-center pt-5 login_child"><img src="{{ uploaded_asset(get_setting('password_reset_page_image')) }}" alt="{{ translate('Password Reset Page Image') }}" class="img-fluid leftbg"></div>
                    </div>
                    <div class="col-md-5 align-self-center">
                        <div class="flex-wrap">
                            <div class="d-flex">
                                <div class="w-100">
                                    <p class="fw-bold text-center text-danger"></p>
                                    <h3 class="mb-4">{{ translate('Reset Password') }}</h3>
                                    {{ translate('Enter your email address and new password and confirm password.') }}

                                </div>
                            </div>
                            <div class="signin-form">
                                <form class="form-default" role="form" action="{{ route('password.update') }}" method="POST">
                                    @csrf    
                                    
                                    <div class="form-floating mb-4">
                                        <input type="email" class="form-control rounded-0{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{  translate('Email') }}" name="email" required><label for="email">{{ translate('Email') }} *</label>
                                        @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-floating mb-4">
                                        <input id="code" type="text" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" value="{{ $email ?? old('code') }}" placeholder="{{translate('Code')}}" required autofocus><label for="name">{{ translate('Code') }} *</label>
                                        @if ($errors->has('code'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-floating mb-0">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password" id="password"  class="form-control rounded-0 " placeholder="{{  translate('Password') }}" required><label for="password">{{ translate('New Password') }} *</label>
                                        @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="form-floating mt-4">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password_confirmation" class="form-control rounded-0" id="password_confirmation" placeholder="{{  translate('Confirm Password') }}" required><label for="password_confirmation">{{ translate('Confirm Password') }} *</label>
                                        @if ($errors->has('confirm_password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('confirm_password') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                    <div class="col mt-3">
                                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Reset Password') }}</button></div>
                                    </div>
                                </form>
                            </div>
                        
                    <div class="mt-3 mr-4 mr-md-0 d-flex justify-content-center">
                        <a href="{{ url()->previous() }}" class="fs-14 fw-700 d-flex align-items-center" style="max-width: fit-content;">
                            <i class="las la-arrow-left fs-20 mr-1"></i>
                            {{ translate('Back to Previous Page')}}
                        </a>
                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection