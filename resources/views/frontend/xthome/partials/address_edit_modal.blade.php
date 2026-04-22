<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="modal-body">
        <div class="flex-grow-1 pl-3 text-left">
            <div class="row">
                <div class="fs-14 col-12 col-lg-4 fw-700">{{ translate('Address') }}</div>
                <div class="col-12">
                    <div class="form-floating mb-4">
                        <textarea type="text" class="form-control textarea-form" id="edit_address_text" value="" name="address"
                            onblur="this.value = this.value.trim()===''?'':this.value;" placeholder="{{ translate('Your Address') }}"
                            rows="5" required>{{ $address_data->address }}</textarea>
                        <label for="edit_address_text">{{ translate('Address') }} <span class="text-danger">
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
                        <input type="text" id="edit_postal_code" name="postal_code" class="form-control"
                            placeholder="{{ translate('Your Postal Code') }}"
                            onblur="this.value = this.value.trim()===''?'':this.value;"
                            value="{{ $address_data->postal_code }}" required>
                        <label for="edit_postal_code">{{ translate('Postal code') }} <span class="text-danger">
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
                            id="edit_country_id"
                            data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                            <option value="">{{ translate('Select your country') }}</option>
                            @foreach (get_active_countries() as $key => $country)
                                <option value="{{ $country->id }}" @if ($address_data->country_id == $country->id) selected @endif>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="edit_country_id">{{ translate('Country') }} <span class="text-danger">
                                *</span></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-floating mb-2">
                        <select class="form-control form-select aiz-selectpicker" data-live-search="true"
                            id="edit_state_id"
                            name="state_id" required>
                            @foreach ($states as $key => $state)
                                <option value="{{ $state->id }}" @if ($address_data->state_id == $state->id) selected @endif>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        <label for="edit_state_id">{{ translate('State') }} <span class="text-danger">*</span></label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-floating mb-2">
                        <select class="form-control form-select aiz-selectpicker" data-live-search="true" id="edit_city_id" name="city_id"
                            required>

                            @foreach ($cities as $key => $city)
                                <option value="{{ $city->id }}" @if ($address_data->city_id == $city->id) selected @endif>
                                    {{ $city->name }}
                                </option>
                            @endforeach

                        </select>
                        <label for="edit_city_id">{{ translate('City') }} <span class="text-danger">
                                *</span></label>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control" id="edit_phone"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" value="{{ $address_data->phone }}"
                            value="" placeholder="Phone No." required>
                        <label for="edit_phone">Phone No. <span class="text-danger"> *</span></label>
                        @if ($errors->has('phone'))
                            <p class="text-danger" role="alert">
                                <small>{{ $errors->first('phone') }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group"><button type="submit" class="theme-btn-two w-100">SUBMIT</button></div>
                </div>
            </div>
        </div>
    </div>
</form>
