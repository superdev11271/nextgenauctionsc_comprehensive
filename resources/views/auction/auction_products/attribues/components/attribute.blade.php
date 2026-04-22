<div class="row">
    @foreach ($attributes as $attribute)
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 control-label" for="estimate_end">{{ $attribute->fields_name }}
                    @if ($attribute->field_optional == 1)
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <div class="col-sm-9">
                    @if ($attribute->field_type == 1)
                        <input type="text" placeholder="{{ $attribute->fields_name }}"
                            name="field[{{ $attribute->id }}][value]" value="{{ $attribute->value($product_id) }}"
                            class="form-control" @required($attribute->field_optional == 1)>
                    @elseif ($attribute->field_type == 2)
                        <textarea cols="20" rows="5" name="field[{{ $attribute->id }}][value]" class="form-control"
                            @required($attribute->field_optional == 1)>{{ $attribute->value($product_id) }}</textarea>
                    @elseif ($attribute->field_type == 3)

                    @if($attribute->fields_name != "City")
                    {{-- Default Behaviour Field --}}
                    <select name="field[{{ $attribute->id }}][value]" class="form-control" @required($attribute->field_optional == 1)>
                        <option value="">Select Type</option>
                        @foreach (explode(',', substr($attribute->dd_value, 0, -1)) as $index => $value)
                        <option value="{{ $value }}" @selected($attribute->value($product_id) == $value)>{{ $value }}
                        </option>
                        @endforeach
                    </select>
                    {{-- Default Behaviour Field --}}
                    @else
                        {{-- Add City field with custom Values --}}
                        <select name="field[{{ $attribute->id }}][value]" class="form-control aiz-selectpicker" data-live-search="true" @required($attribute->field_optional == 1)>
                            <option value="">Select City</option>
                            @php
                            $cities = getCachedApprovedCities();
                            @endphp
                            @foreach ($cities as $city)
                            <option value="{{ $city->name }}" @selected($attribute->value($product_id) == $city->name)>{{ $city->name }}
                            </option>
                            @endforeach
                        </select>
                        {{-- Add City field with custom Values --}}
                    @endif
                    @elseif ($attribute->field_type == 4)
                        @foreach (explode(',', substr($attribute->dd_value, 0, -1)) as $index => $value)
                            <label class="radio-inline">
                                <input type="radio" class="" name="field[{{ $attribute->id }}][value]"
                                    value="{{ $value }}" @required($attribute->field_optional == 1) @checked($attribute->value($product_id) == $value) />
                                {{ $value }}</label>
                        @endforeach
                    @elseif ($attribute->field_type == 5)
                    @foreach (explode(',', substr($attribute->dd_value, 0, -1)) as $index => $value)
                    <label class="checkbox-inline">
                                <input type="checkbox" class="attributecheckbox{{ $attribute->id }}"
                                @if ($attribute->field_optional == 1) onclick="checkrequired('{{ $attribute->id }}')" @endif
                                name="checkbox[{{ $attribute->id }}][value][]" value="{{ $value }}"
                                data-isRequired="{{$attribute->field_optional == 1?1:0}}"
                                data-id={{$attribute->id}}
                                    @required($attribute->field_optional == 1) @checked(str_contains($attribute->value($product_id), $value)) />
                                <input type="hidden" name="checkbox[{{ $attribute->id }}][isrequired]"
                                value="{{ $attribute->field_optional }}" />
                                <input type="hidden" name="checkbox[{{ $attribute->id }}][fieldname]"
                                value="{{ $attribute->fields_name }}" />
                                {{ $value }}</label>
                                @endforeach
                                <br>
                                <span class="error-msg errorid{{$attribute->id}} w-100" style="color: red; font-size: 12px;"></span>

                    @elseif ($attribute->field_type == 0)
                        <div class="">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                        {{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="field[{{ $attribute->id }}][value]"
                                    class="selected-files attributeUpload"
                                    data-isrequired="{{ $attribute->field_optional }}"
                                    value="{{ $attribute->value($product_id) }}" @required($attribute->field_optional == 1)>
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    @endif

                    @if ($attribute->field_type != 5)
                        <input type="hidden" name="field[{{ $attribute->id }}][fieldname]"
                            value="{{ $attribute->fields_name }}">
                        <input type="hidden" name="field[{{ $attribute->id }}][isrequired]"
                            value="{{ $attribute->field_optional }}">
                    @endif

                </div>
            </div>
        </div>
    @endforeach
</div>
