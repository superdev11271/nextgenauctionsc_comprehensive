@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush

@section('content')

<section class="shop-section account-details pt-5">
   <div class="auto-container">
      <div class="row">
         @include('frontend.xthome.partials.xt-customer-sidebar')
         <div class="col-lg-8 col-xxl-9">
         <form action="{{ route('user.profile.update-password') }}" method="POST">
            @csrf
                    <div class="card">
                        <div class="card-header py-3">
                            <h5 class="m-0">{{__('Change your password')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 mb-3">
                                    <div class="form-floating">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="password_old" id="password_old" oninput="this.value = this.value.replace(/\s/g, '')" class="form-control"
                                            placeholder="{{ translate('Old Password') }}" ><label for="password_old"
                                            class="form-label">{{ translate('Old Password') }}<span class="text-danger">*</span></label>
                                            @if ($errors->has('password_old'))
                                            <p class="text-danger" role="alert">
                                                <small>{{ $errors->first('password_old') }}</small>
                                            </p>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-floating">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" id="new_password"
                                            class="form-control" placeholder="{{ translate('New Password') }}" oninput="this.value = this.value.replace(/\s/g, '')" name="new_password" ><label for="password_1"
                                            class="form-label">{{ translate('New Password') }}<span class="text-danger">*</span></label>
                                            @if ($errors->has('new_password'))
                                    <p class="text-danger" role="alert">
                                        <small>{{ $errors->first('new_password') }}</small>
                                    </p>
                                    @endif
                                    </div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <div class="form-floating">
                                        <span class="view-password"><i class="far fa-eye-slash" aria-hidden="true"></i> </span>
                                        <input type="password" name="confirm_password" oninput="this.value = this.value.replace(/\s/g, '')" id="confirm_password"
                                            class="form-control" max=""placeholder="{{ translate('Confirm Password') }}" ><label
                                            for="{{ translate('Confirm Password') }}" class="form-label">{{ translate('Retype Password') }}<span
                                                class="text-danger">*</span></label></div>
                                                @if ($errors->has('confirm_password'))
                                                <p class="text-danger" role="alert">
                                                    <small>{{ $errors->first('confirm_password') }}</small>
                                                </p>
                                                @endif
                                </div>
                                <div class="col-12 pt-2"><button type="submit" class="theme-btn-one">{{__('Change password')}}</button></div>
                            </div>
                        </div>
                    </div>
                </form>
         </div>
      </div>
   </div>
</section>

@endsection