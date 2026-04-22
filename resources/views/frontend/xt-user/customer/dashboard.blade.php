@extends('frontend.layouts.xt-app')
@push('css')
<link href="{{static_asset('xt-assets')}}/css/account-details.css" rel="stylesheet">
@endpush

@push('js')
<script>
        $(document).ready(function() {
            $.validator.addMethod('customWhitespaceValidation', function(value, element) {
                return this.optional(element) || /\S/.test(value);
            }, 'Whitespaces are not allowed.');


            $("#profile_update_form").validate({
                rules: {
                    name: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    first_name: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    last_name: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    email: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    phone: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                },
                messages: {
                    name: {
                        required: "<span class='text-danger'>Please enter name</span>",
                        customWhitespaceValidation: "<span class='text-danger'>Please enter name</span>",
                    },
                    first_name: {
                        required: "<span class='text-danger'>Please enter first name</span>",
                        customWhitespaceValidation: "<span class='text-danger'>Please enter first name</span>",
                    },
                    last_name: {
                        required: "<span class='text-danger'>Please enter last name</span>",
                        customWhitespaceValidation: "<span class='text-danger'>Please enter last name</span>",
                    },
                    email: {
                        required: "<span class='text-danger'>Please enter email</span>",
                        customWhitespaceValidation: "<span class='text-danger'>Please enter email</span>",
                    },
                    phone: {
                        required: "<span class='text-danger'>Please enter phone</span>",
                        customWhitespaceValidation: "<span class='text-danger'>Please enter phone</span>",
                    },
                },
                tooltip_options: {
                    subject: {
                        placement: 'top',
                        html: true
                    },
                    details: {
                        placement: 'top',
                        html: true
                    },
                }
            });

        });

        function toggleBusinessFields() {
            if ($('input[name="is_business"]:checked').val() === '1') {
                $('#business-fields').show();
            } else {
                $('#business-fields').hide();
            }
        }

        $('input[name="is_business"]').on('change', toggleBusinessFields);
        toggleBusinessFields();
    </script>
@endpush

@section('content')

<!-- account details -->
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
                <form id="profile_update_form" action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card mb-5">
                        <div class="card-header py-3">
                            <h5 class="m-0">{{ translate('Basic Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control"  oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="{{ translate('Your Name') }}" name="name" id="name" value="{{ Auth::user()->name }}" required><label for="name">{{ translate('Your Name') }}
                                        <span class="text-danger">*</span></label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control"  oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="{{ translate('First Name') }}" name="first_name" id="first_name" value="{{ Auth::user()->first_name }}" required><label for="first_name">{{ translate('First Name') }}
                                        <span class="text-danger">*</span></label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control"  oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" placeholder="{{ translate('Last Name') }}" name="last_name" id="last_name" value="{{ Auth::user()->last_name }}" required><label for="last_name">{{ translate('Last Name') }}
                                        <span class="text-danger">*</span></label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="number" class="form-control"
                                        placeholder="{{ translate('Your Phone')}}" name="phone" value="{{ Auth::user()->phone }}"><label for="number">{{ translate('Your Phone')}}</label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="email" class="form-control"  placeholder="{{ translate('Your Email')}}" name="email" value="{{ Auth::user()->email }}" required><label for="email">{{ translate('Your Email')}}<span class="text-danger">*</span></label></div>
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
                                        <input type="hidden" name="photo" value="{{ Auth::user()->avatar_original }}" class="selected-files">
                                    </div>
                                </div>

                                <div class="col-sm-4 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Street Number') }}" name="street_number" value="{{ Auth::user()->street_number }}"><label>{{ translate('Street Number') }}</label></div>
                                </div>
                                <div class="col-sm-8 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Street Name') }}" name="street_name" value="{{ Auth::user()->street_name }}"><label>{{ translate('Street Name') }}</label></div>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Suburb') }}" name="suburb" value="{{ Auth::user()->suburb }}"><label>{{ translate('Suburb') }}</label></div>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Postcode') }}" name="postal_code" value="{{ Auth::user()->postal_code }}"><label>{{ translate('Postcode') }}</label></div>
                                </div>
                                <div class="col-sm-4 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('State') }}" name="state" value="{{ Auth::user()->state }}"><label>{{ translate('State') }}</label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Driving Licence Number') }}" name="govt_id" value="{{ Auth::user()->govt_id }}"><label>{{ translate('Driving Licence Number') }}</label></div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="input-group form-control" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend pt-1">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium rounded-0">Browse</div>
                                        </div>
                                        <div class="file-amount px-2 pt-2">Driving Licence Photo</div>
                                        <input type="hidden" name="photoID" value="{{ Auth::user()->id_photo }}" class="selected-files">
                                    </div>
                                </div>

                                <div class="col-sm-12 mb-2">
                                    <label class="mr-3">{{ translate('Business Account') }}:</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_business" value="1" {{ Auth::user()->is_business ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ translate('Yes') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_business" value="0" {{ !Auth::user()->is_business ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ translate('No') }}</label>
                                    </div>
                                </div>
                                <div id="business-fields" class="col-12">
                                    <div class="row">
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Business Name') }}" name="business_name" value="{{ Auth::user()->business_name }}"><label>{{ translate('Business Name') }}</label></div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('ABN/CAN') }}" name="abn_can" value="{{ Auth::user()->abn_can }}"><label>{{ translate('ABN/CAN') }}</label></div>
                                        </div>
                                        <div class="col-sm-4 mb-3">
                                            <div class="form-floating"><input type="text" class="form-control" placeholder="{{ translate('Business Phone') }}" name="business_phone" value="{{ Auth::user()->business_phone }}"><label>{{ translate('Business Phone') }}</label></div>
                                        </div>
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

                <h5>{{ __('Uploaded Document') }}</h5>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex gap-4">
                            <div class="doc_thumb curser-pointer" data-toggle="modal" data-target="#imageViewModal">
                                <div><img class="img-thumbnail" src="{{ static_asset(Auth::user()->idPhoto?->file_name) }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></div>
                                <div>View Photo</div>
                                <!-- <span>{{ __('Driving Licence Photo') }}</span> -->
                            </div>
                            <div class="mt-2">
                                <h5>{{__('Driving Licence Number')}}:</h5> <p>{{Auth::user()->govt_id ?? 'N/A'}}</p>
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
