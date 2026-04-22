@extends('backend.layouts.app')
@section('css')
<style>
    .read_only_attachment .remove {
        display: none
    }
</style>
@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h3 class="mb-0 h6">{{ translate('Relist Auction Product') }}</h5>
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
        <form class="form form-horizontal mar-top" action="{{ route('auction.relist_auction_store') }}"
            method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="POST">
            <input type="hidden" name="id" value="{{ $product->id }}">
            <input type="hidden" name="lang" value="{{ $lang }}">
            <input type="hidden" name="unit" value="{{ 'NA' }}">
            @csrf
            <input type="hidden" name="auction_product_id" value="{{ $product->id }}">
            <input type="hidden" name="added_by" value="admin">
            <div class="row gutters-5">
                <div class="col-lg-8">
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
                                        @foreach ($products as $lot)
                                            <option value="{{ $lot->auction_number }}">
                                                {{ $lot->getFormattedAuctionNumber() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" class="form-control" id="auction_number" name="auction_number"
                            value="{{ $auctionNumber }}">

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Lot No.') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="lotInput" name="lot"
                                        value="{{ old('lot', $product->lot) }}"
                                        placeholder="{{ translate('Lot (e.g. Lot 1, Lot 2 etc)') }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Auction Lable.') }}
                                    <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip" title="This field is shared across all lots associated with the current auction number."></i>
                                </label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="auction_label" name="auction_label"
                                        value="{{ $product->auction_label }}"
                                        placeholder="{{ "Ex: Jone's Clearing Sale or Truck Fleet sale" }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Auction Banner') }}
                                    <i class="las la-question-circle text-danger fs-18" data-toggle="tooltip" title="This field is shared across all lots associated with the current auction number."></i>

                                </label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount" id='banner_image_count'>{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="banner_image" id="banner_image" class="selected-files"
                                            value="{{ $product->banner_image }}"
                                            >
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
                                <label class="col-lg-3 col-from-label">{{ translate('Product Name') }} <i
                                        class="las la-language text-danger"
                                        title="{{ translate('Translatable') }}"></i></label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ translate('Product Name') }}"
                                        value="{{ $product->getTranslation('name', $lang) }}" oninput="this.value=this.value.replace(/[^A-Za-z\s0-9]/g,'')" required>
                                </div>
                            </div>
                            <div class="form-group row" id="brand">
                                <label class="col-lg-3 col-from-label">{{ translate('Brand') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="brand_id" id="brand_id"
                                        data-live-search="true">
                                        <option value="">{{ translate('Select Brand') }}</option>
                                        @foreach (\App\Models\Brand::all() as $brand)
                                            <option value="{{ $brand->id }}"
                                                @if ($product->brand_id == $brand->id) selected @endif>
                                                {{ $brand->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row d-none">
                                <label class="col-md-3 col-from-label">{{ translate('Weight') }}
                                    <small>({{ translate('In Kg') }})</small></label>
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="weight"
                                        value="{{ $product->weight }}" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Tags') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control aiz-tag-input" name="tags[]" id="tags"
                                        value="{{ $product->tags }}" placeholder="{{ translate('Type to add a tag') }}"
                                        data-role="tagsinput">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Thumbnail Image') }}
                                    <small>(290x300)</small></label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="thumbnail_img" value="{{ $product->thumbnail_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Videos') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Provider') }}</label>
                                <div class="col-lg-8">
                                    <select class="form-control aiz-selectpicker" name="video_provider"
                                        id="video_provider">
                                        <option value="youtube" <?php if ($product->video_provider == 'youtube') {
                                            echo 'selected';
                                        } ?>>{{ translate('Youtube') }}</option>
                                        <option value="dailymotion" <?php if ($product->video_provider == 'dailymotion') {
                                            echo 'selected';
                                        } ?>>{{ translate('Dailymotion') }}
                                        </option>
                                        <option value="vimeo" <?php if ($product->video_provider == 'vimeo') {
                                            echo 'selected';
                                        } ?>>{{ translate('Vimeo') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Video Link') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="video_link"
                                        value="{{ $product->video_link }}" placeholder="{{ translate('Video Link') }}">
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
                                <label class="col-lg-3 col-from-label">{{ translate('Starting Bidding Price') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" placeholder="{{ translate('Starting Bidding Price') }}"
                                        name="starting_bid" class="form-control" value="{{ $product->starting_bid }}"
                                        required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-from-label">{{ translate('Bidding Reserved Price') }}</label>
                                <div class="col-md-9">
                                    <input type="text"
                                        value="{{ $product->reserved_price }}"
                                        placeholder="{{ translate('Bidding Reserved Price') }}" name="reserved_price"
                                        class="form-control">
                                </div>
                            </div>

                            @php
                                $start_date = date('d-m-Y H:i:s', $product->auction_start_date);
                                $end_date = date('d-m-Y H:i:s', $product->auction_end_date);
                            @endphp

                            <div class="form-group row">
                                <label class="col-sm-3 col-from-label"
                                    for="start_date">{{ translate('Auction Date Range') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" id="auction_date_range" class="form-control aiz-date-range"
                                        name="auction_date_range"
                                        value="{{ old('auction_date_range') }}"
                                        placeholder="{{ translate('Select Date') }}" data-time-picker="true"
                                        data-format="DD-MM-Y HH:mm:ss" data-separator=" to " autocomplete="off"
                                        data-past-disable="true" required readonly>
                                </div>
                            </div>


                            <!-- <div class="form-group row">
                                <label class="col-sm-3 control-label"
                                    for="estimate_start">{{ translate('Estimate Start') }} <span
                                        class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ translate('Enter Estimate Start') }}"
                                        name="estimate_start"
                                        value="{{ old('estimate_start', $product->estimate_start) }}"
                                        class="form-control"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                        required />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 control-label" for="estimate_end">{{ translate('Estimate End') }}
                                    <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" placeholder="{{ translate('Enter Estimate End') }}"
                                        name="estimate_end" value="{{ old('estimate_end', $product->estimate_end) }}"
                                        class="form-control"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                        required />
                                </div>
                            </div> -->

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Category') }}</h5>
                            <h6 class="float-right fs-13 mb-0">
                                {{ translate('Select Main') }}
                                <span class="position-relative main-category-info-icon">
                                    <i class="las la-question-circle fs-18 text-info"></i>
                                    <span
                                        class="main-category-info bg-soft-info p-2 position-absolute d-none border">{{ translate('This will be used for commission based calculations and homepage category wise product Show.') }}</span>
                                </span>
                            </h6>
                        </div>
                        <div class="card-body ">
                            <div class="h-240px overflow-auto c-scrollbar-light">
                                @php
                                    $old_categories = $product->categories()->pluck('category_id')->toArray();
                                @endphp
                                <ul class="hummingbird-treeview-converter list-unstyled"
                                    data-checkbox-name="category_ids[]" data-radio-name="category_id">
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
                            <h5 class="mb-0 h6 dropdown-toggle" data-toggle="collapse"
                                data-target="#collapse_2">
                                {{ translate('Shipping Configuration') }}
                            </h5>
                        </div>
                        <div class="card-body collapse show" id="collapse_2">
                            @if (get_setting('shipping_type') == 'product_wise_shipping')
                                <div class="form-group row">
                                    <label class="col-lg-6 col-from-label">{{ translate('Pickup') }}</label>
                                    <div class="col-lg-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="free"
                                                @if ($product->shipping_type == 'free') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-lg-6 col-from-label">{{ translate('Home Delivery') }}</label>
                                    <div class="col-lg-6">
                                        <label class="aiz-switch aiz-switch-success mb-0">
                                            <input type="radio" name="shipping_type" value="flat_rate"
                                                @if ($product->shipping_type == 'flat_rate') checked @endif>
                                            <span></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="flat_rate_shipping_div" style="display: none">
                                    <div class="form-group row">
                                        <label class="col-lg-6 col-from-label">{{ translate('Shipping cost') }}</label>
                                        <div class="col-lg-6">
                                            <input type="number" lang="en" min="0"
                                                value="{{ $product->shipping_cost }}" step="0.01"
                                                placeholder="{{ translate('Shipping cost') }}" name="flat_shipping_cost"
                                                class="form-control">
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

                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Cash On Delivery') }}</h5>
                        </div>
                        <div class="card-body">
                            @if (get_setting('cash_payment') == '1')
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-md-6 col-from-label">{{ translate('Status') }}</label>
                                            <div class="col-md-6">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input type="checkbox" name="cash_on_delivery" value="1"
                                                        @if ($product->cash_on_delivery == 1) checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p>
                                    {{ translate('Cash On Delivery option is disabled. Activate this feature from here') }}
                                    <a href="{{ route('activation.index') }}"
                                        class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index', 'shipping_configuration.edit', 'shipping_configuration.update']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Cash Payment Activation') }}</span>
                                    </a>
                                </p>
                            @endif
                        </div>
                    </div>

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
                                    <input type="number" class="form-control" name="est_shipping_days"
                                        value="{{ $product->est_shipping_days }}" min="1" step="1"
                                        placeholder="{{ translate('Shipping Days') }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                            id="inputGroupPrepend">{{ translate('Days') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


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
                                         value="{{ $product->pickup_days }}"
                                        placeholder="{{ translate('Pickup date') }}">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                            id="inputGroupPrepend">{{ translate('Date') }}</span>
                                    </div>
                                </div>

                                <div class="input-group mt-2">
                                    <input type="text" class="form-control" id="picktime" name="pickup_time" required
                                        value="{{ $product->pickup_time }}" placeholder="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"
                                            id="inputGroupPrepend">{{ translate('Time') }}</span>
                                    </div>
                                </div>

                                <div class="input-group mt-2">
                                    <input type="text" class="form-control" name="pickup_address" id="pickup_address"  required
                                         value="{{ $product->pickup_address }}"
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
                                    {{ $tax->name }}
                                    <input type="hidden" value="{{ $tax->id }}" name="tax_id[]">
                                </label>

                                @php
                                    $tax_amount = 0;
                                    $tax_type = '';
                                    foreach ($tax->product_taxes as $row) {
                                        if ($product->id == $row->product_id) {
                                            $tax_amount = $row->tax;
                                            $tax_type = $row->tax_type;
                                        }
                                    }
                                @endphp

                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <div class="input-group mt-2">
                                        <input type="number" lang="en" min="0" value="{{ $tax_amount }}"
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
                                            <option value="amount" @if ($tax_type == 'amount') selected @endif>
                                                {{ translate('Flat') }}
                                            </option>
                                            <option value="percent" @if ($tax_type == 'percent') selected @endif>
                                                {{ translate('Percent') }}
                                            </option>
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
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('PDF Specification') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="pdf" value="{{ $product->pdf }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Attributes') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-12">
                                    <span id="AttributeFields"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="row gutters-5">
                <div class="col-lg-12 col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Product Description') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-12">
                                    <textarea class="aiz-text-editor" name="description">{{ $product->getTranslation('description', $lang) }}</textarea>
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
                                <label class="col-lg-3 col-from-label">{{ translate('Meta Title') }}</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" name="meta_title"
                                        value="{{ $product->meta_title }}"
                                        placeholder="{{ translate('Meta Title') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-from-label">{{ translate('Description') }}</label>
                                <div class="col-lg-8">
                                    <textarea name="meta_description" rows="8" class="form-control">{{ $product->meta_description }}</textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label"
                                    for="signinSrEmail">{{ translate('Meta Images') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image"
                                        data-multiple="true">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">
                                                {{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="meta_img" value="{{ $product->meta_img }}"
                                            class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Slug') }}</label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="{{ translate('Slug') }}" id="slug"
                                        name="slug" value="{{ $product->slug }}" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="card d-none">
                        <div class="card-header">
                            <h5 class="mb-0 h6">{{ translate('Terms & conditions') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-12">
                                    <textarea class="aiz-text-editor" name="terms_conditions"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="col-12">
                    <div class="mb-3 text-right">
                        <button type="submit" onclick="checkRequiredAttributes()" name="button" id="submitButton"
                            class="btn btn-info">{{ translate('Update Product') }}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('script')
    <!-- Treeview js -->
    <script src="{{ static_asset('assets/js/hummingbird-treeview.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            show_hide_shipping_div();

            $("#treeview").hummingbird();
            $('input[name="category_id"][type="radio"]').on("change", function() {
                showCategoryAttribues($(this).val(), product_id);
            });
            var main_id = '{{ $product->category_id != null ? $product->category_id : 0 }}';

            var selected_ids = "{{ implode(',', $old_categories) }}";

            const myArray = selected_ids.split(",");
            for (let i = 0; i < myArray.length; i++) {
                const element = myArray[i];
                $('#treeview input:checkbox#' + element).prop('checked', true);
                $('#treeview input:checkbox#' + element).parents("ul").css("display", "block");
                $('#treeview input:checkbox#' + element).parents("li").find('.las').removeClass("la-plus").addClass(
                    'la-minus');
            }
            $('#treeview input:radio[value=' + main_id + ']').prop('checked', true);

        });

        $("[name=shipping_type]").on("change", function() {
            show_hide_shipping_div();
        });

        var pickupDate = "{{ $product->pickup_days ?? '' }}";

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


        $('#auction_date_range').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('DD-MM-YYYY HH:mm:ss');
                var endDate = picker.endDate.format('DD-MM-YYYY HH:mm:ss');
                var pickupDate = moment(endDate, 'DD-MM-YYYY HH:mm:ss').add(3, 'days');
                $('#pickdate').val(pickupDate.format('DD-MM-YYYY'));
        });

        function show_hide_shipping_div() {
            var shipping_val = $("[name=shipping_type]:checked").val();
            $(".flat_rate_shipping_div").hide();
            $("#pickup_section").show();
            $('#pickup_section input').prop('disabled', false);
            $("#shipping_days").hide();
            if (shipping_val == 'flat_rate') {
                $(".flat_rate_shipping_div").show();
                $("#pickup_section").hide();
                $('#pickup_section input').prop('disabled', true);
                $("#pickup_section_heading").hide();
                $("#shipping_days").show();

            }
        }

        var defaultPickupTime = "{{ $product->pickup_time ?? '' }}";

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

        AIZ.plugins.tagify();

        $(document).ready(function() {
            $('.remove-files').on('click', function() {
                $(this).parents(".col-md-4").remove();
            });
        });

        let category_id = '{{ $product->category_id }}';
        let product_id = '{{ $product->id }}';

        function showCategoryAttribues(category_id, product_id = null) {
            $.ajax({
                type: "POST",
                url: "{{ route('auction.get_attributes_by_subcategory') }}",
                data: `category_id=${category_id}&_token={{ csrf_token() }}&product_id=${product_id}`,
                success: function(data) {
                    $('#AttributeFields').html(data.view);
                    AIZ.uploader.previewGenerate();
                    AIZ.plugins.bootstrapSelect('refresh');

                }
            });
        }

        $(document).ready(function() {
            showCategoryAttribues(category_id, product_id);
        });

        function checkrequired(id) {
            let checked = $(".attributecheckbox" + id + ":checked").length;
            if (checked > 0) {
                $(".attributecheckbox" + id).removeAttr('required');
                $(".errorid" + id).text('');
            } else {
                $(".attributecheckbox" + id).attr('required', 'required');
                $(".errorid" + id).text('Attribute is required.');
            }
        }

        function checkRequiredAttributes() {
            let showAlert = false;
            $(".attributeUpload").each(function(index, element) {
                if (element.getAttribute('data-isrequired') == 1 && element.value == "") {
                    event.preventDefault();
                    $(element).next('.error-msg').remove();
                    $(element).after('<span class="error-msg w-100" style="color: red; font-size: 12px;">Attribute is required.</span>');
                    showAlert = true;
                }else{
                    $(element).next('.error-msg').remove();
                }
            });
            if (showAlert) AIZ.plugins.notify('danger', 'Attrubute is required.');

            $("[data-isRequired='1']").each(function () {
                let cbid = $(this).data("id")
                checkrequired(cbid)
            });
            return showAlert;
        }

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
                    startDate: "{{ $lastProduct->pickup_days ?? '' }}",
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
