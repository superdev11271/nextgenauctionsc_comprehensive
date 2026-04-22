<div class="modal fade" id="login_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Login')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </div>
            <div class="modal-body">
                <div class="flex-wrap">
                    <!-- <div class="d-flex">
                        <div class="w-100">
                            <p class="fw-bold text-center text-danger"></p>
                            <h3 class="mb-4">{{ translate('Login to your account')}}</h3>
                        </div>
                    </div> -->
                    <div class="signin-form">
                        <div class="error alert alert-danger d-none" id="login-form-errors"></div>
                        <form class="form-default" id="login-form">
                            @csrf
                            <div class="form-floating mb-4">
                                <div class="form-floating mb-4"><input type="email" id="modal_login_email" name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"  placeholder="{{  translate('johndoe@example.com') }}"><label for="modal_login_email">{{ translate('Email') }}<span class="text-danger">*</span></label></div>
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{!! $errors->first('email') !!}}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-floating mb-0">
                                <div class="form-floating mb-0">
                                    <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                    <input type="password" id="modal_login_password" name="password" class="form-control"  placeholder="{{  translate('Password') }}" autocomplete="off"><label for="modal_login_password">{{ translate('Password') }} <span class="text-danger">*</span></label></div>
                                <i class="password-toggle las la-2x la-eye"></i>
                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div class="form-check text-start my-3 d-flex justify-content-between">
                                    <div><input class="form-check-input" type="checkbox" i><label class="form-check-label" for="">{{ translate('Remember Me') }}</label></div>
                                    <div>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#exampleModal" class="form-check-label forgetlink">{{ translate('Forgot password?')}}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group"><button id="login-btn" type="button" class="theme-btn-two w-100 text-uppercase">{{ translate('Login') }}</button></div>
                            </div>
                        </form>
                    </div>
                    <!-- Social Login -->

                    <p class="text-center pt-4 login-small">{{ translate('Dont have an account?')}} <a href="{{ route('user.registration') }}">{{ translate('Register Now')}}</a></p>
                </div>

            </div>
        </div>
    </div>
</div>



<div class="modal" tabindex="-1" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ translate('Forgot password?')}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form-default" role="form" action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-4">
                        <input type="email" id="modal_forgot_email" required class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="{{translate('johndoe@example.com') }}" name="email"  autocomplete="off"><label for="modal_forgot_email">{{  translate('Email') }}</label>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="col">
                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Send Password Reset Link') }}</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
