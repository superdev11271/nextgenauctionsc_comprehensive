@extends('backend.layouts.app')

@section('css')
<style>
    .read_only_attachment .remove {
        display: none
    }
</style>
@endsection
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Add New Auction Product') }}</h5>
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
        <form id="auction-product-add-form" class="form form-horizontal mar-top" action="{{ route('auction_product_store.admin') }}" method="POST"
            enctype="multipart/form-data" id="choice_form">
            <div class="row gutters-5">
                <div class="col-lg-8">
                    @csrf
                    <input type="hidden" name="added_by" value="admin">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Auction Collection Settings') }}</h5>
                        </div>
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Auction No.') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="" id="auctionSelect"
                                        onchange="updateLotNo()">
                                        <option value= "{{ $auctionNumber }}#new">New Auction</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->auction_number }}">
                                                {{ $product->getFormattedAuctionNumber() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Lot No.') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="lotInput" name="lot"
                                        value="{{ old('lot', '001') }}"
                                        placeholder="{{ translate('Lot (e.g. Lot 1, Lot 2 etc)') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Auction Lable.') }}
                                    <i class="las la-question-circle text-danger fs-18 not-editable" style="display: none;" data-toggle="tooltip" title="You can modify this field in edit mode."></i>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="auction_label" name="auction_label"
                                    value=""
                                    placeholder="{{ "Ex: Jone's Clearing Sale or Truck Fleet sale"}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                for="signinSrEmail">{{ translate('Auction Banner') }}
                                <i class="las la-question-circle text-danger fs-18 not-editable" style="display: none;" data-toggle="tooltip" title="You can modify this field in edit mode."></i>
                                </label>
                                    <div class="col-md-8">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                    {{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount" id="banner_image_count">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="banner_image" value="" id="banner_image" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Information') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Product Name') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="{{ translate('Product Name') }}"  oninput="this.value=this.value.replace(/[^A-Za-z\s0-9]/g,'')" onchange="update_sku()"
                                        value="{{ old('name') }}">
                                    <span id="name-error text-danger"></span>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-md-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}" @selected(old('brand_id') == $brand->id)>
                                                {{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" class="form-control" id="auction_number" name="auction_number"
                                value="{{ $auctionNumber }}">


                            <div class="form-group row d-none">
                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                                    <small>({{ translate('In Kg') }})</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight" value="{{ old('weight') }}"
                                        step="0.01" value="0.00" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 c1ol-from-label">{{ translate('Tags') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]"
                                        placeholder="{{ translate('Type and hit enter to add a tag') }}">
                                    <small
                                        class="text-muted">{{ translate('This is used for search. Input those words by which cutomer can find this product.') }}</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                    <small>(300x300)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                    <small
                                        class="text-muted">{{ translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Bidding Price + Date Range') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Starting Bidding Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="number" lang="en" min="1" value="1" step="0.01"
                                        value="{{ old('starting_bid') }}"
                                        placeholder="{{ translate('Starting bidding price') }}" id="starting_bid" name="starting_bid"
                                        class="form-control">
                                        <span id="starting_bid-error text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Bidding Reserved Price') }}</label>
                                <div class="col-md-9">
                                    <input type="text" value="{{ old('reserved_price') }}"
                                        placeholder="{{ translate('Bidding Reserved Price') }}" id="reserved_price" name="reserved_price"
                                        class="form-control">
                                     <span id="reserved_price-error text-danger"></span>
                                </div>
                            </div>


                            {{-- {{ dd($firstProductAuctionNumber->start_date) }} --}}
                            <div class="form-group row">
                                <label class="col-sm-3 control-label"
                                    for="start_date">{{ translate('Auction Date Range') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" id="auction_date_range" class="form-control aiz-date-range"
                                        name="auction_date_range"
                                        value="{{ old('auction_date_range') }}"
                                        placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                        data-past-disable="true" required readonly>
                                    <span id="auction_date_range-error" class="text-danger"></span>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label class="col-sm-3 control-label"
                                    for="estimate_start">{{ translate('Estimate Start') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ translate('Enter Estimate Start') }}"
                                        name="estimate_start" value="{{ old('estimate_start') }}" class="form-control"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                        id="estimate_start" />
                                        <span id="estimate_start-error" class="text-danger"></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="estimate_end">{{ translate('Estimate End') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ translate('Enter Estimate End') }}"
                                        name="estimate_end" value="{{ old('estimate_end') }}" class="form-control"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                        id="estimate_end" />
                                        <span id="estimate_end-error" class="text-danger"></span>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-md-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube">{{ translate('Youtube') }}</option>
                                        <option value="dailymotion">{{ translate('Dailymotion') }}</option>
                                        <option value="vimeo">{{ translate('Vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="video_link"
                                        placeholder="{{ translate('Video Link') }}">
                                    <small
                                        class="text-muted">{{ translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.") }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Category') }}
                            </h5>

                            <h6 class="float-right fs-13 mb-0">
                                {{ translate('Select Main') }}
                                <span class="position-relative main-category-info-icon">
                                    <i class="las la-question-circle fs-18 text-info"></i>
                                    <span
                                        class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                                </span>
                            </h6>
                        </div>
                        <div id="category-error"  class="alert alert-danger d-none" role="alert">
                            Please select a Main product category, and the Sub category must be within the selected category.
                        </div>

                        <div class="card-body">

                            <div class="h-250px overflow-auto c-scrollbar-light">
                                <ul class="hummingbird-treeview-converter list-unstyled"
                                    data-checkbox-name="category_ids[]" data-radio-name="category_id"
                                    data-keep-first-submenu-open="true" data-remove-parent-category-radio="true">
                                    @foreach ($categories as $category)
                                        <li id="{{ $category->id }}">{{ $category->name }}</li>
                                        @foreach ($category->childrenCategories as $childCategory)
                                            @include('backend.product.products.child_category', [
                                                'child_category' => $childCategory,
                                            ])
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">
                                {{ translate('Shipping Configuration') }}
                            </h5>
                        </div>

                        <div class="card-body">
                            @if (get_setting('shipping_type') == 'product_wise_shipping')
                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Pickup') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free" checked>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-6 col-from-label">{{ translate('Home Delivery') }}</label>
                                    <div class="col-md-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="flat_rate">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flat_rate_shipping_div" style="display:none">
                                    <div class="form-group row">
                                        <label class="col-md-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                        <div class="col-md-6">
                                            <input type="number" lang="en" min="0" value="0"
                                                step="0.01" placeholder="{{ translate('Shipping cost') }}"
                                                name="flat_shipping_cost" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p>
                                    {{ translate('Product wise shipping cost is disable. Shipping cost is configured from here') }}
                                    <a href="{{ route('shipping_configuration.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Shipping Configuration') }}</span>
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Cash On Delivery')}}</h5>
                    </div>
                    <div class="card-body">
                        @if (get_setting('cash_payment') == '1')
                            <div class="form-group row">
                                <label class="col-md-6 col-from-label">{{translate('Status')}}</label>
                                <div class="col-md-6">
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="cash_on_delivery" value="1" checked="">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        @else
                            <p>
                                {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                                <a href="{{route('activation.index')}}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index','shipping_configuration.edit','shipping_configuration.update'])}}">
                                    <span class="aiz-side-nav-text">{{translate('Cash Payment Activation')}}</span>
                                </a>
                            </p>
                        @endif
                    </div>
                </div> --}}

                    <div class="card" id="shipping_days" style="display:none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Estimate Shipping Time') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label for="name">
                                    {{ translate('Shipping Days') }}
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="est_shipping_days" min="1"
                                        step="1" value="{{ old('est_shipping_days') }}"
                                        placeholder="{{ translate('Shipping Days') }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                            id="inputGroupPrepend">{{ translate('Days') }}</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="card">
                        <div class="card" id="pickup_section">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Pickup date / Time') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name">
                                        {{ translate('Pickup date / Time') }}
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="pickup_days" id="pickdate" required
                                             value="{{ old('pickup_days',$lastProduct?->pickup_days) }}"
                                            placeholder="{{ translate('Pickup Date') }}" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"
                                                id="inputGroupPrepend">{{ translate('Date') }}</span>
                                        </div>
                                    </div>

                                    <div class="input-group mt-2">
                                        <input type="text" list="time" class="form-control" id="picktime" name="pickup_time" required
                                            value="{{ old('pickup_time',$lastProduct?->pickup_time) }}" placeholder="" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"
                                                id="inputGroupPrepend">{{ translate('Time') }}</span>
                                        </div>
                                    </div>
                                    <div class="input-group mt-2">
                                        <input type="text" class="form-control" name="pickup_address" required
                                             value="{{ old('pickup_address',$lastProduct?->pickup_address ?? '') }}"
                                            placeholder="{{ translate('Pickup Address') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"
                                                id="inputGroupPrepend">{{ translate('Pickup Address') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('Tax') }}</h5>
                            </div>
                            <div class="card-body">
                                @foreach (\App\Models\Tax::where('tax_status', 1)->get() as $tax)
                                    <label for="name">
                                        {{ $tax->name }} <span class="text-danger">*</span>
                                        <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                                    </label>
                                    @php
                                        $tax_amount = 0;
                                        $tax_type = '';
                                        foreach ($tax->product_taxes as $row) {
                                            if ($lastProduct?->id == $row->product_id) {
                                                $tax_amount = $row->tax;
                                                $tax_type = $row->tax_type;
                                            }
                                        }
                                    @endphp
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            {{-- $tax_amount ??  --}}
                                            <div class="input-group mt-2">
                                            <input type="number" lang="en" min="0" value="{{ old('tax.0','10') }}"
                                                step="0.01" placeholder="{{ translate('Tax') }}" name="tax[]"
                                                class="form-control" required>
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"
                                                        id="inputGroupPrepend">(%)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <select class="form-control aiz-selectpicker d-none" name="tax_type[]">
                                                <option value="percent">{{ translate('Percent') }}</option>
                                                {{-- <option value="amount">{{ translate('Flat') }}</option> --}}
                                            </select>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0 h6">{{ translate('PDF Specification') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="input-group" data-toggle="aizuploader" data-type="document">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                    {{ translate('Browse') }}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="pdf" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="AttributeSection" style="display: none">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Attributes') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-12">
                                    <div id="AttributeFields"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                {{-- <label class="col-md-3 col-from-label">{{translate('Description')}}</label> --}}
                                <div class="col-12">
                                    <textarea class="aiz-text-editor" name="description">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0 h6">{{ translate('SEO Meta Tags') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="meta_title"
                                                value="{{ old('meta_title') }}"
                                                placeholder="{{ translate('Meta Title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ translate('Description') }}</label>
                                        <div class="col-md-8">
                                            <textarea name="meta_description" rows="8" class="form-control">{{ old('meta_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label"
                                            for="signinSrEmail">{{ translate('Meta Image') }}</label>
                                        <div class="col-md-8">
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                        {{ translate('Browse') }}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="meta_img" class="selected-files">
                                            </div>
                                            <div class="file-preview box sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                    <div class="col-12">
                        <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                                <button type="submit" name="button" value="draft" onclick="checkRequiredAttributes()"
                                    class="btn btn-primary">{{ translate('Save') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script type="text/javascript">

        $(document).ready(function() {
            $("#treeview").hummingbird();
            $('input[name="category_id"][type="radio"]').on("change", function() {
                showCategoryAttribues($(this).val())
            });

            var pickupDate = "{{ $lastProduct?->pickup_days ?? '' }}";

            if (!pickupDate || pickupDate === '') {
                pickupDate = moment();
            } else {
                pickupDate = moment(pickupDate, 'DD-MM-YYYY');

                if (pickupDate.isBefore(moment(), 'day')) {
                    pickupDate = moment();
                }
            }
            $('#pickdate').daterangepicker({
                singleDatePicker: true,
                startDate: pickupDate,
                locale: {
                    format: 'DD-MM-YYYY'
                },
                minDate: moment(),
            });

            // Initialize the date range picker
            $('#auction_date_range').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('DD-MM-YYYY HH:mm:ss');
                var endDate = picker.endDate.format('DD-MM-YYYY HH:mm:ss');
                var pickupDate = moment(endDate, 'DD-MM-YYYY HH:mm:ss').add(3, 'days');
                $('#pickdate').val(pickupDate.format('DD-MM-YYYY'));
            });

            $("#auction-product-add-form").on("submit",function(event){
                var errors = false;

                var Name = $("#name").val().trim();
                var starting_bid = $("#starting_bid").val().trim();
                var estimate_start = $("#estimate_start").val().trim();
                var estimate_end = $("#estimate_end").val().trim();
                var reserved_price = $("#reserved_price").val().trim();
                var category_selected = $("input[name='category_id']:checked").length;

                var checkedCategories = $("input[name='category_ids[]']:checked").map(function() {
                    return $(this).val();
                }).get();
                // alert(checkedCategories + "  "+category_selected);
                var auction_date_range = $("#auction_date_range").val().trim();

                if (Name === '') {
                    $("#name").addClass('is-invalid');
                    $("#name-error").text("this field is required");
                    errors = true;
                } else {
                    $("#name").removeClass('is-invalid');
                    $("#name-error").text("");
                }

                if (starting_bid === '') {
                    $("#starting_bid").addClass('is-invalid');
                    $("#starting_bid-error").text("this field is required.");
                    errors = true;
                } else {
                    $("#starting_bid").removeClass('is-invalid');
                    $("#starting_bid-error").text("");
                }


                if (estimate_start === '') {
                    $("#estimate_start").addClass('is-invalid');
                    $("#estimate_start-error").text("this field is required.");
                    errors = true;
                } else {
                    $("#estimate_start").removeClass('is-invalid');
                    $("#estimate_start-error").text("");
                }

                if (estimate_end === '') {
                    $("#estimate_end").addClass('is-invalid');
                    $("#estimate_end-error").text("this field is required.");
                    errors = true;
                } else {
                    $("#estimate_end").removeClass('is-invalid');
                    $("#estimate_end-error").text("");
                }

                if (category_selected === 0 || checkedCategories.length == 0) {
                    $("#category-error").removeClass('d-none');
                    $("input[name='category_id']").first().focus();
                    $("input[name='category_ids[]']").first().focus();
                    errors = true;
                } else {
                    $("#category-error").addClass('d-none');
                }

                if(auction_date_range == ""){
                    $("#auction_date_range").addClass('is-invalid');
                    $("#auction_date_range-error").text("this field is required.");
                    errors = true;
                }else{
                    $("#auction_date_range").removeClass('is-invalid');
                    $("#auction_date_range-error").text("");
                }


                if (errors) {
                    event.preventDefault();
                    $('.is-invalid').first().focus();
                }
            });

        });

        // $('form').bind('submit', function(e) {
        //     // Disable the submit button while evaluating if the form should be submitted
        //     $("button[type='submit']").prop('disabled', true);

        //     var valid = true;

        //     if (!valid) {
        //         e.preventDefault();

        //         // Reactivate the button if the form was not submitted
        //         $("button[type='submit']").button.prop('disabled', false);
        //     }
        // });

        $("[name=shipping_type]").on("change", function() {
            $(".flat_rate_shipping_div").hide();
            $("#pickup_section").show();
            $('#pickup_section input').prop('disabled', false);
            $("#shipping_days").hide();
            if ($(this).val() == 'flat_rate') {
                $(".flat_rate_shipping_div").show();
                $("#pickup_section").hide();
                $('#pickup_section input').prop('disabled', true);
                $("#pickup_section_heading").hide();
                $("#shipping_days").show();

            }
        });



        var defaultPickupTime = "{{ $lastProduct?->pickup_time ?? '' }}";

        $('#picktime').daterangepicker({
            timePicker: true,
            timePickerIncrement: 1,
            locale: {
                format: 'hh:mm A'
            },
            startDate: defaultPickupTime ? moment(defaultPickupTime, 'hh:mm A') : moment().hours(12).minutes(0).seconds(0),  // Use pickup_time or default
            endDate: defaultPickupTime ? moment(defaultPickupTime, 'hh:mm A') : moment().hours(23).minutes(59).seconds(0)   // Set end date to 11:59 PM by default
        }).on('show.daterangepicker', function (ev, picker) {
            picker.container.find(".calendar-table").hide();
        });


        function showCategoryAttribues(id) {
            $.ajax({
                type: "POST",
                url: "{{ route('auction.get_attributes_by_subcategory') }}",
                data: "category_id=" + id + "&_token={{ csrf_token() }}",
                success: function(data) {
                    if (data.status) {
                        $('#AttributeSection').show();
                        $('#AttributeFields').html(data.view);
                        AIZ.uploader.previewGenerate();
                        AIZ.plugins.bootstrapSelect('refresh');
                    } else {
                        $('#AttributeSection').hide();
                    }
                }
            });
        }

        function checkrequired(id) {
            let checked = $(".attributecheckbox" + id + ":checked").length;
            if (checked > 0) {
                $(".attributecheckbox" + id).removeAttr('required');
            } else {
                $(".attributecheckbox" + id).attr('required', 'required');
            }
        }

        function checkRequiredAttributes() {
            $(".attributeUpload").each(function(index, element) {
                if (element.getAttribute('data-isrequired') == 1 && element.value == "") {
                    event.preventDefault();
                    $(element).next('.error-msg').remove();
                    $(element).after('<span class="error-msg w-100" style="color: red; font-size: 12px;">Attribute is required.</span>');
                    AIZ.plugins.notify('danger', 'Attrubute is required.');
                    return false
                }
            });
        }

        // function toggleFields() {
        //     var checkbox = document.getElementById('toggleCheckbox');
        //     var dropdownField = document.getElementById('dropdownField');
        //     var auctionNumberField = document.querySelector('input[name="auction_number"]');
        //     if (checkbox.checked) {
        //     dropdownField.style.display = 'block';
        //     auctionNumberField.style.display = 'none';
        // } else {
        //     dropdownField.style.display = 'none';
        //     auctionNumberField.style.display = 'block';
        // }
        // }

        function updateLotNo() {
            const auctionSelect = document.getElementById('auctionSelect');
            const lotInput = document.getElementById('lotInput');
            const selectedValue = auctionSelect.value.split('#');
            const dateRangePickerElement = $('#auction_date_range');

            if (selectedValue[1] === 'new') {
                document.getElementById('auction_date_range').value = '';
                lotInput.value = '001';
                $("#auction_label").val("");
                $("#auction_label").prop('readonly', false);
                // Initialize the date range picker using aiz-date-range class
                dateRangePickerElement.daterangepicker({
                    timePicker: true,
                    timePicker24Hour: true,
                    timePickerSeconds: true,
                    autoUpdateInput: false,
                    locale: {
                        format: 'DD-MM-YYYY HH:mm:ss',
                        separator: ' to ',
                        applyLabel: 'Apply',
                        cancelLabel: 'Cancel',
                    },
                    minDate: moment().startOf('day') // disable past dates
                }, function(start, end, label) {
                    dateRangePickerElement.val(start.format('DD-MM-YYYY HH:mm:ss') + ' to ' + end.format(
                        'DD-MM-YYYY HH:mm:ss'));
                }).removeAttr('readonly disabled');
                document.getElementById('auction_number').value = selectedValue[0];
                $('#banner_image').val(null);
                $('#banner_image').closest('.input-group').next(".file-preview").html('');
                $('#banner_image').closest('.input-group').next(".file-preview").removeClass('read_only_attachment');
                $('#banner_image_count').html('Choose file');
                $('#banner_image').closest('.input-group').show();
                $('.not-editable').hide();
                AIZ.uploader.initForInput();
                AIZ.uploader.previewGenerate();

                $('#pickdate').daterangepicker({
                    singleDatePicker: true,
                    startDate: "{{ $lastProduct?->pickup_days ?? '' }}",
                    locale: {
                        format: 'DD-MM-YYYY'
                    },
                    minDate: moment(),
                });
            } else {
                $.ajax({
                    url: "{{ route('check.auction.number') }}",
                    type: "GET",
                    data: {
                        auction_number: selectedValue[0]
                    },
                    success: function(response) {
                        lotInput.value = String(response.count + 1).padStart(3, '0');


                        document.getElementById('auction_date_range').value = response.selected_date;
                        $("#auction_label").val(response.product.auction_label);
                        $("#auction_label").prop('readonly', true);

                        // Destroy the existing date range picker instance
                        if (dateRangePickerElement.data('daterangepicker')) {
                            dateRangePickerElement.data('daterangepicker').remove();
                        }

                        dateRangePickerElement.attr({
                            'readonly': true
                        });
                        document.getElementById('auction_number').value = selectedValue[0];
                        $('#banner_image').val(response.product.banner_image);
                        $('#banner_image').closest('.input-group').next(".file-preview").html('');
                        $('#banner_image').closest('.input-group').hide();
                        $('.not-editable').show();
                        $('#banner_image_count').html('Choose file');
                        AIZ.uploader.previewGenerate();
                        $('#banner_image').closest('.input-group').next(".file-preview").addClass('read_only_attachment');

                        var endDate = moment.unix(response.product.auction_end_date).add(3, 'days');
                        $('#pickdate').daterangepicker({
                            singleDatePicker: true,
                            startDate: response.product.pickup_days || endDate,
                            locale: {
                                format: 'DD-MM-YYYY'
                            },
                            minDate: moment(),
                        });
                    }
                });
            }
        }
    </script>

@endsection
