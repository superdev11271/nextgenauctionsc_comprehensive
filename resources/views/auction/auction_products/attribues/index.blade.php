@extends('backend.layouts.app')
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate(isset($attribute) ? 'Edit Attribute' : 'Add Attribute') }}</h5>
    </div>
    <div class="">
        <!-- Error Meassages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row gutters-5">
            <div class="col-lg-12">
                <div class="card">
                    {{-- <div class="card-header">
                        <h5 class="mb-0 h6">{{ translate('Attribute Information') }}</h5>
                    </div> --}}
                    <div class="card-body">
                        <form role="form" class="form-horizontal" id="AddAttributeForm"
                            action="{{ isset($attribute) ? route('auction.attibute.update', $attribute->id) : route('auction.attibute.store') }}"
                            method="post">
                            @csrf
                            <div class="row AddAttributeLoader">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="group" class="control-label">Sub
                                            Category</label>
                                        <div>
                                            <select class='form-control' name="category_id"
                                                onchange="showCategoryAttribues(this.value)" id="category_id"
                                                @disabled(isset($attribute)) required>
                                                <option value="">Select Sub Category</option>
                                                @foreach ($parent_categories as $parent)
                                                    <optgroup label='{{ $parent->name }}'>

                                                    {{-- <option value="{{ $parent->id }}" @selected(old('category_id', $attribute ?? session('lastCategory')) == $parent->id)>
                                                        {{ $parent->name }}
                                                    </option> --}}

                                                    @foreach ($parent?->childrenCategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}" @selected(old('category_id', $attribute ?? session('lastCategory')) == $subcategory->id)>
                                                            {{-- {{ $parent->name }}->  --}}
                                                            {{ $subcategory->name }}
                                                        </option>
                                                    @endforeach
                                                    </optgroup>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!------------------------today------------------->

                                    <div class="d-flex gap-12 w-100 justify-content-between">
                                        <div class="w-100">
                                            <div class="form-group">
                                                <label for="inputEmail3" class="control-label"> Attribute type</label>
                                                <div>
                                                    <select class='form-control w-100' name="field_type"
                                                        onclick="getoptions(this.value);" required id="field_type">
                                                        <option value="1" @selected(old('field_type', $attribute->field_type ?? '') == 1)> Short text (Input
                                                            Field)</option>
                                                        <option value="2" @selected(old('field_type', $attribute->field_type ?? '') == 2)> Detailed text
                                                            (Textarea)</option>
                                                        <option value="3" @selected(old('field_type', $attribute->field_type ?? '') == 3)> Drop down</option>
                                                        <option value="4" @selected(old('field_type', $attribute->field_type ?? '') == 4)> Single Selection
                                                            (Radio
                                                            Buttion)
                                                        </option>
                                                        <option value="5" @selected(old('field_type', $attribute->field_type ?? '') == 5)> Multiple Selection
                                                            (Checkbox)
                                                        </option>
                                                        <option value="0" @selected(old('field_type', $attribute->field_type ?? '') == 0)> Upload (Image)
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="add_values_details w-100"
                                            @if (old('field_type', $attribute->field_type ?? '') <= '3') style="display: none;" @endif>
                                            <div class="form-group">
                                                <label for="subcategoryname" class=" control-label"
                                                    id="LabelDynamicField">DD
                                                    Values</label>
                                                <div>
                                                    <div class="d-flex">
                                                        <input type="text" name="dd_value" value="" class="form-control  w-100"
                                                            id="ddvalues" placeholder="" style="display: inline;" />
                                                        <button type="button" name="add" onclick="addvalue(this.value);"
                                                            class="btn btn-success"
                                                            style="margin-top: -2px;"><strong>Add</strong></button>

                                                    </div>
                                                    <span id="ddvalueerror"></span>
                                                    <div id="dropdownvaluesajax" class="dropdownvaluesajax">
                                                        @if (isset($attribute) && $attribute?->dd_value)
                                                            @foreach (explode(',', substr($attribute->dd_value, 0, -1)) as $index => $value)
                                                                <div id="optionid{{ $index }}">
                                                                    <button
                                                                        class="btn btn-soft-danger btn-icon btn-circle btn-sm m-2"
                                                                        onclick="deleteoption({{ $index }},'{{ $value }}')"
                                                                        type="button" title="Delete">
                                                                        <i class="las la-trash"></i>
                                                                    </button>
                                                                    <strong>{{ $index + 1 }}) {{ $value }} </strong>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <input type="hidden" name="hidenvalues"
                                                    value="{{ $attribute->dd_value ?? '' }}" class="form-control"
                                                    id="hidenvalues" placeholder="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <!------------------------today------------------->
                                    <div class="form-group w-100">
                                        <label for="subcategoryname" class="control-label">Attribute
                                            Title </label>
                                        <div>
                                            <input type="text" name="fields_name" class="form-control" id="fieldsname"
                                                value="{{ old('fields_name', $attribute->fields_name ?? '') }}"
                                                placeholder="Attribute Title " required />
                                        </div>
                                    </div>
                                    <div class="form-group w-100">
                                        <label for="inputEmail3" class="control-label">Optional</label>
                                        <div>
                                            <input type="radio" name="field_optional" value="1"
                                                @checked(old('field_optional', $attribute->field_optional ?? '') == 1)>
                                            Yes &nbsp;
                                            <input type="radio" name="field_optional" value="2"
                                                @checked(old('field_optional', $attribute->field_optional ?? 2) == 2)>
                                            No
                                        </div>
                                    </div>

                                    <div class="box-footer">
                                        <div class="form-group w-100">
                                            <div class="d-flex justify-content-end">
                                                @isset($attribute)
                                                    <a href="{{ route('auction.attibutes') }}" name="button"
                                                        class="mx-2 btn btn-danger btn-md rounded-2 fs-14 fw-700 shadow-danger action-btn text-white">Cancel</a>
                                                @endisset
                                                <input type="submit"
                                                    class="mx-2 btn btn-success  btn-md rounded-2 fs-14 fw-700 shadow-success action-btn" name="AddAttribute" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="card">
        <div class="card-header row gutters-5">
            <div class="col">
                <h5 class="mb-md-0 h6">{{ translate('Auction Product Attributes') }}</h5>
            </div>
        </div>

        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Attribute Name</th>
                        <th>Type</th>
                        <th>Values</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="attribute_list">
                    @isset($attribute_list)
                        @foreach ($attribute_list as $attribute)
                            <tr>
                                <td>{{ $attribute->id }}</td>
                                <td>{{ $attribute->fields_name }} {!! $attribute->field_optional == 1 ? "<span class='text-danger'>*</span>" : '' !!}</td>
                                <td>{{ $attribute->field_type_str() }}</td>
                                <td>{{ $attribute->dd_value }}</td>
                                <td>
                                    <a class='btn btn-soft-primary btn-icon btn-circle btn-sm'
                                        href='{{ route('auction.attibute.edit', encrypt($attribute->id)) }}' title='Edit'>
                                        <i class='las la-edit'></i></a>

                                    <a class='btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete' href='#'
                                        data-href='{{ route('auction.attribute.delete', encrypt($attribute->id)) }}' title='Delete'>
                                        <i class='las la-trash'></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script type="text/javascript">
        function getoptions(id) {
            let hasDDValues = ["3", "4", "5"].includes(id)
            let lbl = (id == '3' ? "DD Values" : (id == '4' ? "Radio Button Values" : "Check Box Values"));
            if (hasDDValues) {
                $('.add_values_details').show();
            } else {
                $(".add_values_details").hide();
            }
            $("#LabelDynamicField").html(lbl)
        }

        function addvalue() {

            var ddvalues = $("#ddvalues").val();
            var hidenvalues = $('#hidenvalues').val();

            // var current_values = ddvalues + ',' + hidenvalues;
            // if(hidenvalues)
            var current_values = hidenvalues + ddvalues + ',';


            if (ddvalues == "") {
                $("#ddvalueerror").html("<span class='text-danger'>Please enter drop down value</span>")
            }

            if (ddvalues != "") {
                $.ajax({
                    type: "POST",
                    url: "{{ route('auction.add.ddvalue') }}",
                    data: "current_values=" + current_values +
                        "&action=AddDropDownValue&_token={{ csrf_token() }}",

                    success: function(data) {
                        $("#ddvalueerror").html('');
                        $('#dropdownvaluesajax').html(data);

                        $('#hidenvalues').val(current_values);
                        $('#ddvalues').val('');
                    }
                });
            }
        }

        function showCategoryAttribues(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('auction.showcategory.attributes') }}",
                data: "category_id=" + id + "&_token={{ csrf_token() }}",

                success: function(data) {
                    // There was an error when i was returning from update function to let the category selected and their attribute listed i have to use this approach if i dont use this approach the table was not rendered when it was redirected from the update function. if u can fix it please do so.
                    setTimeout(() => {
                        $("#attribute_list").html(data);
                        $('.aiz-table').footable();
                    }, {{ session()->has('lastCategory') ? 1000 : 0 }});

                    setTimeout(() => {
                        AIZ.extra.deleteConfirm();
                    }, {{ session()->has('lastCategory') ? 1500 : 500 }});
                }
            });
        }

        function deleteoption(containerId, stringtoremove) {
            $("#optionid" + containerId).remove()
            let string = $('#hidenvalues').val();
            string = string.replace(stringtoremove + ",", "")
            $('#hidenvalues').val(string);
        }

        $(function() {
            @if (session()->has('lastCategory'))
                showCategoryAttribues('{{ session('lastCategory') }}')
            @endif
        });
    </script>
@endsection
