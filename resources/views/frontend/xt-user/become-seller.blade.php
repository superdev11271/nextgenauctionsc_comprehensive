@php
    $userAddress = Auth::user()->addresses;
    $defaultShippingAddress = $userAddress->where('set_default', 1)->where('address_type','1')->first();
    $defaultBillingAddress = $userAddress->where('set_default', 1)->where('address_type','2')->first();
    $bankDetails =  Auth::user()->shop?->bank_name && Auth::user()->shop?->bank_acc_name && Auth::user()->shop?->bank_acc_no && Auth::user()->shop?->bank_routing_no;
    $basicInfo =   auth()->user()->name;

@endphp


@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.3/tutorials/timelines/timeline-1/assets/css/timeline-1.css"> -->
<style>
   
    .swal2-popup {
        background-color: #333 !important; 
        color: #BE800F !important; 
        border-radius: 0 !important; 
    }
    .swal2-title {
        color: #BE800F !important;
    }
    .swal2-html-container {
        color: #BE800F !important; 
    }
    .swal2-actions .swal2-confirm {
        background-color: #BE800F !important; 
        color: #fff !important; 
    }
    .swal2-actions .swal2-cancel {
        background-color: #BE800F !important; 
        color: #fff !important; 
    }
    .swal2-checkbox-container {
        text-align: left; 
    }
    .swal2-checkbox-container input[type="checkbox"] {
        margin-right: 10px; 
    }
</style>
@endpush

@section('content')

<!-- account details -->
<h1 class="visually-hidden">{{ translate('Become a Seller') }}</h1>

<section class="shop-section account-details pt-5">
    <div class="auto-container">
        <div class="row">
            @include('frontend.xthome.partials.xt-customer-sidebar')

            <div class="col-lg-8 col-xxl-9">
                @if(auth()->user()->shop?->remark && auth()->user()->shop?->verification_status == 0)
                    <div class="alert alert-danger" role="alert">
                    <i class="fa fa-warning"></i> {{ auth()->user()->shop?->remark }}
                    </div>
                @endif
                <div class="card mb-5">
                    <div class="card-header py-3">
                        <h5 class="m-0">{{ translate('Become Seller!') }}</h5>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <section class="bsb-timeline-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-12 col-xl-12">

                                <ul class="timeline">
                                    <li class="timeline-item">
                                        <div class="timeline-body">
                                            <div class="timeline-content">
                                                <div class="card border-0">
                                                    <div class="card-body p-0">
                                                        <h5 class="card-subtitle text-secondary mb-1">{{__('Basic Infomation')}} @if($basicInfo) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                        <p class="card-text m-0">{{__('Complete your basic information')}} <a href="{{route('dashboard')}}" class="text-decoration-underline">here</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="timeline-item">
                                        <div class="timeline-body">
                                            <div class="timeline-content">
                                                <div class="card border-0">
                                                    <div class="card-body p-0">
                                                        <h5 class="card-subtitle text-secondary mb-1">{{__('Add Shipping Address')}} @if($defaultShippingAddress) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                        <p class="card-text m-0">{{__('Add Shipping address')}} <a href="{{route("customer.addresses")}}" class="text-decoration-underline">here</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="timeline-item">
                                        <div class="timeline-body">
                                            <div class="timeline-content">
                                                <div class="card border-0">
                                                    <div class="card-body p-0">
                                                        <h5 class="card-subtitle text-secondary mb-1">{{__('Add billing Address')}} @if($defaultBillingAddress) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                        <p class="card-text m-0">{{__('Add billing address')}} <a href="{{route("customer.addresses")}}" class="text-decoration-underline">here</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    @if($defaultShippingAddress && $defaultBillingAddress && $basicInfo)
                                        @php
                                            $user = auth()->user();
                                        @endphp
                                        @if(!$user->shop)
                                            <li class="timeline-item">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <form action="{{ route('become.seller') }}" id="become-seller"  method="POST">
                                                                    @csrf
                                                                    <button type="submit" id="submit-button" class="theme-btn-one">{{ __('Request to become a seller') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @elseif(!$user->shop?->verification_status)
                                            @if(Auth::user()->shop?->verification_info == null)
                                                <li class="timeline-item">
                                                    <div class="timeline-body">
                                                        <div class="timeline-content">
                                                            <div class="card border-0">
                                                                <div class="card-body p-0">
                                                                    <div class="alert alert-success" role="alert">
                                                                        <p class="text-secondary">{{ __('Shop Created please fill verification form') }} <i class="fa fa-check-circle"></i></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif

                                            <li class="timeline-item">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <h5 class="card-subtitle text-secondary mb-1">{{__('Download this pdf form and submit it')}} @if(Auth::user()->shop?->agrement_form) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                                @if(Auth::user()->shop?->agrement_form)
                                                                    <p class="card-text m-0">{{__('Your Uploaded form')}} <a href="{{ uploaded_asset(auth()->user()->shop?->agrement_form) }}" class="text-decoration-underline">View</a></p>
                                                                @else
                                                                    <p class="card-text m-0">{{__('please fill all necessary fields')}} <a href="{{ route('client-aggrement') }}" class="text-decoration-underline">Download</a></p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @if(!(Auth::user()->shop?->agrement_form) || Auth::user()->shop?->rejected == 1)
                                                        <form  action="{{ route('seller.shop.update') }}" method="POST" enctype="multipart/form-data">
                                                            <input type="hidden" name="shop_id" value="{{ auth()->user()->shop?->id }}">
                                                            @csrf
                                                            <div class="row mt-2">
                                                                <div class="col-md-6">
                                                                    <div class="input-group">
                                                                        <label class="form-control h-48 d-flex gap-2 items-center">
                                                                            <div class="input-group-text theme-btn-card rounded-0 d-flex items-center">Browse</div>
                                                                            <div>User Aggrement Form</div>
                                                                            <input type="file" class="invisible form-control rounded-0 position-absolute"  accept=".pdf,.jpg" name="aggrement_form">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <button type="submit" class="theme-btn-one btn-primary btn-sm m-1">Upload</button>

                                                                </div>
                                                            </div>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </div>
                                            </li>
                                            @if(Auth::user()->shop?->agrement_form)
                                            <li class="timeline-item">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <h5 class="card-subtitle text-secondary mb-1">{{__('Verification form')}} @if(!(Auth::user()->shop?->verification_info == null)) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                                <p class="card-text m-0">{{__('please fill all necessary fields')}} <a href="{{ route('apply-verification') }}" class="text-decoration-underline">here</a></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif

                                            @if(!(Auth::user()->shop?->verification_info == null))
                                            <li class="timeline-item">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <h5 class="card-subtitle text-secondary mb-1">{{__('Everything Done! please wait for approval!')}} @if(!(Auth::user()->shop?->verification_info == null)) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                        @else
                                            <li class="timeline-item">
                                                <div class="timeline-body">
                                                    <div class="timeline-content">
                                                        <div class="card border-0">
                                                            <div class="card-body p-0">
                                                                <div class="alert alert-success" role="alert">
                                                                    <p class="text-secondary">{{ __('congratulation! Shop Approved!') }} <i class="fa fa-check-circle"></i></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endif

                                    @if(auth()->user()->shop?->verification_status)
                                    <li class="timeline-item">
                                        <div class="timeline-body">
                                            <div class="timeline-content">
                                                <div class="card border-0">
                                                    <div class="card-body p-0">
                                                        <h5 class="card-subtitle text-secondary mb-1">{{__('Fill Your Bank Details')}} @if($bankDetails) <i class="fa fa-check-circle"></i> @else <i class="fa fa-times-circle"></i> @endif</h5>
                                                        <p class="card-text m-0">{{__('Banking Information')}} <a href="{{route("bank-info")}}" class="text-decoration-underline">here</a> </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @endif

                                </ul>
                            </div>

                        </div>
                    </div>
                </section>
            </div>


        </div>
    </div>
</section>

@endsection

@section('script')
    
<script>
    $(document).ready(function() {
        document.getElementById('submit-button').addEventListener('click', function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                html: `
                    <p>Do you want to request to become a seller?</p>
                    <div class="swal2-checkbox-container">
                        <input type="checkbox" id="terms-checkbox">
                        <label for="terms-checkbox">I agree to the <a href="{{route('sellerpolicy')}}">terms and conditions</a></label>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true,
                customClass: {
                    popup: 'swal2-popup',
                    title: 'swal2-title',
                    htmlContainer: 'swal2-html-container',
                    confirmButton: 'swal2-confirm',
                    cancelButton: 'swal2-cancel'
                },
                preConfirm: () => {
                    const termsCheckbox = Swal.getPopup().querySelector('#terms-checkbox');
                    if (!termsCheckbox.checked) {
                        Swal.showValidationMessage('You need to agree to the terms and conditions before submitting!');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('become-seller').submit();
                }
            });
        });
    });
</script>
@endsection