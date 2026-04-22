@extends('auth.layouts.xt-authentication')

@section('content')

    <section class="shop-section">
            <div class="auto-container">
               <div class="row">
                  <div class="col-md-12">
                     <div class="wrap d-md-flex login_child">
                        <div class="col-md-7">
                           <div class="text-center pt-5 login_child"><img src="{{ uploaded_asset(get_setting('password_reset_page_image')) }}" class="img-fluid leftbg" alt="{{ translate('Password Reset Page Image') }}"></div>
                        </div>
                        <div class="col-md-5 align-self-center">
                           <div class="flex-wrap">
                              <div class="d-flex">
                                 <div class="w-100">
                                    <p class="fw-bold text-center text-danger"></p>
                                    <h3 class="mb-4">{{ translate('Verify Your Email Address') }}</h3>
                                 </div>
                              </div>
                              <div class="signin-form">
                              {{ translate('Before proceeding, please check your email for a verification link. If you did not receive the email.') }}
                                @if (session('resent'))

                                @endif
                              </div>
                              <p class="text-center pt-4 login-small">
                                <a href="{{ route('verification.resend') }}" class="theme-btn-two w-100" >{{ translate('Click here to request another') }}</a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>

@endsection