
<div class="modal" tabindex="-1" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                        <input type="email" id="forgot_password_email" required class="form-control"
                        {{-- {{ $errors->has('email') ? ' is-invalid' : '' }} " --}}
                          value="{{ old('email') }}" placeholder="{{translate('johndoe@example.com') }}" name="email"  autocomplete="email"><label for="forgot_password_email">{{  translate('Email') }}</label>
                    {{-- <div id="div1">
                        @if ($errors->has('email'))
                            <span class="text-danger" role="alert" >
                                <small>{{ $errors->first('email') }}</small>
                            </span>
                        @endif
                    </div> --}}
                    </div>

                    <div class="col">
                        <div class="form-group"><button type="submit" class="theme-btn-two w-100">{{ translate('Send Password Reset Link') }}</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

