<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="form-default" role="form" id="addressmodal" action="{{ route('addresses.store') }}"
                method="POST">
                @csrf
                <div class="modal-body">
                    <div class="flex-grow-1 pl-3 text-left">
                        <div class="row">
                            <div class="fs-14 col-12 col-lg-4 fw-700">{{ translate('Address') }}</div>
                            <div class="col-12">
                                <div class="form-floating mb-4">
                                    <textarea type="text" class="form-control textarea-form" id="new_address_text" value="" name="address"
                                        placeholder="{{ translate('Your Address') }}" rows="5"
                                        onblur="this.value = this.value.trim()===''?'':this.value;" required>{{ old('address') }}</textarea>
                                    <label for="new_address_text">{{ translate('Address') }} <span class="text-danger">
                                            *</span></label>
                                    @if ($errors->has('address'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('address') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-4">
                                    <input type="text" id="new_postal_code" name="postal_code" class="form-control"
                                        placeholder="{{ translate('Your Postal Code') }}" name="postal_code"
                                        onblur="this.value = this.value.trim()===''?'':this.value;"
                                        value="{{ old('postal_code') }}" required>
                                    <label for="new_postal_code">{{ translate('Postal code') }} <span class="text-danger">
                                            *</span></label>
                                    @if ($errors->has('postal_code'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('postal_code') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-2">
                                    <select class="form-control form-select aiz-selectpicker" data-live-search="true"
                                        id="new_country_id"
                                        data-placeholder="{{ translate('Select your country') }}" name="country_id"
                                        required>
                                        <option value="">{{ translate('Select your country') }}</option>
                                        @foreach (get_active_countries() as $key => $country)
                                            <option value="{{ $country->id }}" >
                                                {{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="new_country_id">{{ translate('Country') }} <span class="text-danger">
                                            *</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-2">
                                    <select class="form-control form-select aiz-selectpicker" data-live-search="true"
                                        id="new_state_id"
                                        name="state_id" required>
                                    </select>
                                    <label for="new_state_id">{{ translate('State') }} <span
                                            class="text-danger">*</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-2">
                                    <select class="form-control form-select aiz-selectpicker" data-live-search="true"
                                        id="new_city_id" name="city_id" required>

                                    </select>
                                    <label for="new_city_id">{{ translate('City') }} <span class="text-danger">
                                            *</span></label>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" name="phone" class="form-control" id="new_phone"
                                        placeholder="Phone No." value="{{ old('phone') }}">
                                    <label for="new_phone">Phone No. <span class="text-danger"> *</span></label>
                                    @if ($errors->has('phone'))
                                        <p class="text-danger" role="alert">
                                            <small>{{ $errors->first('phone') }}</small>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check d-flex align-items-center gap-2" id="addressType">
                                    <input class="form-check-input" type="checkbox" value="1" name="address_type"
                                        id="addressCheckbox">
                                    <label class="form-check-label" for="addressCheckbox">Use as shipping
                                        address</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check d-flex align-items-center gap-2" id="address_modal">
                                    <input class="form-check-input d-none" type="checkbox" value="1"
                                        name="address_modal" id="address_modal">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group"><button type="submit"
                                        class="theme-btn-two w-100">SUBMIT</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ translate('Edit Address') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body c-scrollbar-light" id="edit_modal_body">
                @if ($errors->any() && session('redirection_from') == 'edit')
                    {{ view('frontend.' . get_setting('homepage_select') . '.partials.address_edit_modal', session('data')) }}
                @endif
            </div>
        </div>
    </div>
</div>

@section('scriptjs')
    <script type="text/javascript">

        window.onload = function() {
            @if ($errors->any() && session('redirection_from') == 'edit')
                $("#edit-address-modal").modal("show");
            @elseif ($errors->any())
                $("#new-address-modal").modal("show");
            @endif
        };



        $('#new-address-modal').on('show.bs.modal', function(event) {
            var checker = event.relatedTarget?.dataset?.addresstype != undefined ?event.relatedTarget?.dataset?.addresstype:"{{old('address_modal')==1?'shipping':'billing'}}";
            console.debug(checker)
            if (checker == 'shipping') {
                $('#addressType input').val('1');
                $('#addressType label').html('Use as shipping address');

                $('#address_modal input').val('2').prop('checked', true);
            }
            if (checker == 'billing') {
                $('#addressType input').val('2');
                $('#addressType label').html('Use as billing address');

                $('#address_modal input').val('1').prop('checked', true);
            }
        });

        // function add_new_address(addressType){
        //     $('#new-address-modal').modal('show');
        // }

        function edit_address(address) {
            var url = '{{ route('addresses.edit', ':id') }}';
            url = url.replace(':id', address);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat = -33.8688;
                        var long = 151.2195;

                        if (response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat = parseFloat(response.data.address_data.latitude);
                            long = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });

        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-state') }}",
                type: 'POST',
                data: {
                    country_id: country_id
                },
                success: function(response) {
                    console.log(response);
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('get-city') }}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function(response) {
                    var obj = JSON.parse(response);
                    if (obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', e => {
            AIZ.plugins.bootstrapSelect('');
        }, false);
    </script>

    <script>
        $(document).ready(function() {

            $.validator.addMethod('customWhitespaceValidation', function(value, element) {
                return this.optional(element) || /\S/.test(value);
            }, 'Whitespaces are not allowed.');


            $("#addressmodal").validate({
                rules: {
                    address: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    postal_code: {
                        required: true,
                        customWhitespaceValidation: true
                    },
                    phone: {
                        required: true,
                        customWhitespaceValidation: true
                    },

                },
                messages: {
                    address: {
                        required: "Please enter address",
                        customWhitespaceValidation: "Please enter address",
                    },
                    postal_code: {
                        required: "Please enter Postal Code",
                        customWhitespaceValidation: "Please enter Postal Code",
                    },
                    phone: {
                        required: "Please enter phone",
                        customWhitespaceValidation: "Please enter phone",
                    },

                },
                // tooltip_options: {
                //     address: {
                //     placement: 'top',
                //     html: true
                //     },
                //     postal_code: {
                //     placement: 'top',
                //     html: true
                //     },
                //     phone: {
                //     placement: 'top',
                //     html: true
                //     },

                // }
            });

        });
    </script>
    @if (get_setting('google_map') == 1)
        @include('frontend.' . get_setting('homepage_select') . '.partials.google_map')
    @endif
@endsection
