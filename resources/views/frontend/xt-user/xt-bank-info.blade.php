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
            @if ($errors->any())
            <div class="alert alert-danger">
               <ul>
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                  @endforeach
               </ul>
            </div>
            @endif
            <form action="{{ route('seller.profile.update', auth()->user()->id) }}" method="POST">
               <input type="hidden" name="id" value="{{auth()->user()->id ?? ''}}">
               <input type="hidden" name="bank_info" value="bank_info">
               @csrf
               <div class="card mb-5">
                  <div class="card-header py-3">
                     <h5 class="m-0">{{__('Seller Bank Information') }}</h5>
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-sm-6 mb-3">
                           <div class="form-floating"><input type="text" class="form-control" name="bank_name" value="{{auth()->user()->shop?->bank_name ?? ''}}" id="bankname" placeholder="Bank Name"><label for="bankname">{{__('Bank Name')}}<span class="text-danger">*</span></label></div>
                           @FieldError('bank_name')
                        </div>

                        <div class="col-sm-6 mb-3">
                           <div class="form-floating"><input type="text" class="form-control" name="bank_acc_name" value="{{auth()->user()->shop?->bank_acc_name ?? ''}}" id="bank_acc_name" placeholder="Account Holder Name" data-sider-insert-id="db53da59-b173-43fa-b2ff-1edfee0d9c56" data-sider-select-id="2d44171f-61eb-4b0b-9034-0fc2a99602e1"><label for="bank_acc_name">{{__('Account Holder Name')}}<span class="text-danger">*</span></label></div>
                           @FieldError('bank_acc_name')
                        </div>
                        <div class="col-sm-6 mb-3">
                           <div class="form-floating"><input type="text" class="form-control" name="bank_acc_no" value="{{auth()->user()->shop?->bank_acc_no ?? ''}}" id="bank_acc_no" placeholder="Expiry Year" data-sider-insert-id="db53da59-b173-43fa-b2ff-1edfee0d9c56" data-sider-select-id="2d44171f-61eb-4b0b-9034-0fc2a99602e1"><label for="bank_acc_no">{{__('Bank Account Number')}}<span class="text-danger">*</span></label></div>
                           @FieldError('bank_acc_no')
                        </div>
                        <div class="col-sm-6 mb-3">
                           <div class="form-floating"><input type="text" class="form-control" name="bank_routing_no" value="{{auth()->user()->shop?->bank_routing_no ?? ''}}" placeholder="Routing Code" id="routing_code"><label for="routing_code">{{__('Branch Code')}}<span class="text-danger">*</span></label></div>
                           @FieldError('bank_routing_no')
                        </div>

                        <div class="row">
                           <label class="col-md-3 col-form-label">{{ translate('Cash Payment') }}</label>
                           <div class="col-md-9">
                              <label class="aiz-switch aiz-switch-success mb-3">
                                 <input value="1" name="cash_on_delivery_status" type="checkbox" @if (auth()->user()->shop?->cash_on_delivery_status == 1) checked @endif>
                                 <span class="slider round"></span>
                              </label>
                           </div>
                        </div>
                        <div class="row">
                           <label class="col-md-3 col-form-label">{{ translate('Bank Payment') }} <span class="text-danger">*</span></label>
                           <div class="col-md-9">
                              <label class="aiz-switch aiz-switch-success mb-3">
                                 <input value="1" name="bank_payment_status" type="checkbox" @if (auth()->user()->shop?->bank_payment_status == 1) checked @endif>
                                 <span class="slider round"></span>
                              </label>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>



               <div class="col-12 pt-2"><button type="submit" class="theme-btn-one">{{__('Save')}}</button></div>

            </form>
         </div>
      </div>
   </div>
</section>

@endsection
