@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush

@section('content')

<!-- account details -->
<section class="shop-section account-details pt-5">
    <div class="auto-container">
        <div class="d-flex justify-content-end justify-content-sm-end mb-4">
            <a href="{{ url()->previous() }}" class="theme-btn-one">Go Back</a>
        </div>
        <div class="row">
            @include('frontend.xthome.partials.xt-customer-sidebar')
            <div class="col-lg-8 col-xxl-9">
            @if(auth()->user()->shop?->remark && auth()->user()->shop?->verification_status == 0)
            <div class="alert alert-danger" role="alert">
               <i class="fa fa-warning"></i> {{ auth()->user()->shop?->remark }}
            </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-header py-3">
                            <h5 class="m-0">{{ translate('Basic Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Your Name') }}" name="name" autocomplete="name"     oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')"
                                        id="name" value="{{ Auth::user()->name }}" required><label for="name">{{ translate('Your Name') }}
                                        <span class="text-danger">*</span></label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="number" id="seller_profile_phone" class="form-control"
                                        autocomplete="tel"
                                        placeholder="{{ translate('Your Phone')}}" name="phone" value="{{ Auth::user()->phone }}"><label for="seller_profile_phone">{{ translate('Your Phone')}}</label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="email" id="seller_profile_email" class="form-control"  placeholder="{{ translate('Your Email')}}" name="email" autocomplete="email" value="{{ Auth::user()->email }}" required><label for="seller_profile_email">{{ translate('Your Email')}}<span class="text-danger">*</span></label></div>
                                </div>

                                {{-- <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="file" accept=".jpg,.png,.jpeg,.gif" class="form-control" placeholder="{{ translate('Profile Image')}}" name="photo" ><label for="photo">{{ translate('Profile Image') }}
                                        <span class="text-danger">*</span></label></div>
                                </div> --}}

                                <div class="col-sm-6 mb-3">
                                    <div class="input-group form-control" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend pt-1">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium rounded-0">Browse</div>
                                        </div>
                                        <div class="file-amount px-2 pt-2">Choose File</div>
                                        <input type="hidden" name="photo" value="" class="selected-files">
                                    </div>
                                </div>

                                <div class="col-12 pt-2">
                                    {{-- <button class="theme-btn-one">Save changes</button> --}}
                                    <button type="submit" class="theme-btn-one" >Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
          
        
            <form class="" action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="{{ auth()->user()->shop?->id }}">
                @csrf
                    <div class="card mb-5">
                        <div class="card-header py-3">
                            <h5 class="m-0">{{ translate('Business Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Your Name') }}"  name="name" id="name" autocomplete="organization" value="{{ Auth::user()->shop?->name }}" required><label for="name">{{ translate('Business Name') }}
                                        <span class="text-danger">*</span></label></div>
                                    @FieldError('name')
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="input-group form-control" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend pt-1">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium rounded-0">{{__('Business Logo')}}</div>
                                        </div>
                                        <div class="file-amount px-2 pt-2">Choose File</div>
                                        <input type="hidden" name="logo" value="" class="selected-files">
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="number" id="business_phone" class="form-control"
                                        autocomplete="tel"
                                        placeholder="{{ translate('Your Phone')}}"   name="phone" value="{{ Auth::user()->shop?->phone }}"><label for="business_phone">{{ translate('Business Phone')}}<span
                                                class="text-danger">*</span></label></div>
                                        @FieldError('phone')
                                </div>



                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control"   id="address" placeholder="{{ translate('Your Email')}}" name="address" value="{{ Auth::user()->shop?->address }}" required><label for="address">{{ translate('Business Address')}}<span class="text-danger">*</span></label></div>
                                    @FieldError('address')
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" id="gst_number" class="form-control"
                                        placeholder="{{ translate('GST Number')}}"  name="gst_number" value="{{ Auth::user()->shop?->gst_number }}"><label for="gst_number">{{ translate('GST Number')}}<span
                                                class="text-danger">*</span></label></div>
                                </div>

                                <div class="col-12 pt-2">
                                    <button type="submit"  class="theme-btn-one" >{{__('Save changes')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <h5>{{ __('Uploaded Document') }}</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex gap-4">
                            <div class="doc_thumb curser-pointer" data-toggle="modal" data-target="#imageViewModal">
                                <div><img class="img-thumbnail" src="{{ static_asset(Auth::user()->idPhoto?->file_name) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></div>
                                <div>{{__('View Photo')}}</div>
                            </div>
                            <div class="mt-2">
                                <h5>{{__('Driving Licence Number')}}:</h5> <p>{{Auth::user()->govt_id ?? 'N/A'}}</p >
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<div class="modal fade" id="imageViewModal" tabindex="-1" role="dialog" aria-labelledby="imageViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageViewModalLabel">{{ __('Driving Licence Photo') }}</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div><img id="modalImage" src="{{ static_asset(Auth::user()->idPhoto?->file_name) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" style="width: 100%;"></div>

                <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-center mt-3">
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="theme-btn-one">Browse</div>
                            </div>
                            <input type="hidden" name="photoID" value="" class="selected-files">
                        </div>
                        <button type="submit" class="theme-btn-one" >Save changes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
