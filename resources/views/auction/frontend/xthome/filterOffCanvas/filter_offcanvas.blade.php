@php
    $categories = App\Models\Category::getParentCategories();
    $brands = App\Models\Brand::getAllBrands();
@endphp

<div class="offcanvas offcanvas-start shopFilterbar-offcanvas" tabindex="-1" id="shopFilterbar"
    aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel">FILTER</h3>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="filter-area " id="flterSideBar">
            {{-- {{route(request()->route()->getName(), request()?->category?->slug)}} --}}
            <form action="" method="GET" id="search-form">
                <input type="hidden" name="search" value="filter">
                <input type="hidden" name="keywords" value="{{ isset($keywords) ? $keywords : '' }}">
                <input type="hidden" name="sort_by" value="{{ isset($sort_by) ? $sort_by : '' }}">
                <div class="filter-body">
                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filter_1" aria-expanded="true" aria-controls="filter_1">
                            Current Filter
                        </button>
                        <span onclick="clearAllFilter()" class="clear_acc" style="cursor: pointer;">Clear All</span>
                    </div>


                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filter_2" aria-expanded="true" aria-controls="filter_2">
                            Price
                        </button>
                        <div id="filter_2" class="accordion-collapse collapse show">
                            <div class="shop__all__top__right" style="background-image: none">
                                <select class="form-control form-select" onchange="defineRange(this)">
                                    <option data-min="0" value="100000">All</option>
                                    <option data-min="0" value="1000"> 0-1k  </option>
                                    <option data-min="1000" value="5000"> 1k-5k </option>
                                    <option data-min="5000" value="10000"> 5k-10k </option>
                                    <option data-min="10000" value="25000"> 10k-25k </option>
                                    <option data-min="25000" value="50000">25k-50k</option>
                                </select>
                            </div>
                            {{-- <button onclick="defineRange(0,1000)" class="">0-1k</button>
                            <button onclick="defineRange(1000,5000)" class="">1k-5k</button>
                            <button onclick="defineRange(5000,10000)" class="">5k-10k</button>
                            <button onclick="defineRange(0,100000)" class="">All</button> --}}
                            <div class="accordion-body">
                                <div class="filter-search-option">
                                    <div class="sliders_control">
                                        <input id="fromSlider" type="range" name="min_price"
                                            value="{{ isset($_GET['min_price']) ? $_GET['min_price'] : 0 }}"
                                            min="0" max="100000"
                                            {{-- step="5000" --}}
                                            oninput="rangefilterOnchange({min:this.value}, this)"
                                            onchange="filter()"
                                            >
                                        <input id="toSlider" type="range" name="max_price"
                                            oninput="rangefilterOnchange({max:this.value}, this)"
                                            onchange="filter()"
                                            value="{{ isset($_GET['max_price']) ? $_GET['max_price'] : 100000 }}"
                                            {{-- step="5000" --}}
                                            min="0" max="100000.00">
                                    </div>
                                    <div>
                                        <div class="filter_range_value d-flex align-items-center">
                                            <div class="position-relative w-50">
                                                <input type="number" id="fromInput" class="rang-box w-100 "
                                                    onchange="rangefilter({min:this.value})"
                                                    onkeypress="if(event.key === 'Enter'){ rangefilter() }"
                                                    value="{{ isset($_GET['min_price']) ? $_GET['min_price'] : 0 }}"
                                                    min="0" max="100000.00"><span
                                                    class="cr_icon">{{ get_system_currency()->symbol }}</span>
                                            </div>
                                            <div class="position-relative w-50">
                                                <input class="rang-box w-100" type="number" id="toInput" max="$100000.00"
                                                    min="0"
                                                    onkeypress="if(event.key === 'Enter'){ rangefilter() }"
                                                    value="{{ isset($_GET['max_price']) ? $_GET['max_price'] : 100000 }}"
                                                    onchange="rangefilter({max:this.value})"
                                                    ><span
                                                    class="cr_icon">{{ get_system_currency()->symbol }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filter_2" aria-expanded="true" aria-controls="filter_2">
                            Price
                        </button>
                        <div id="filter_2" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="filter-search-option">
                                    <div class="filter-bar_by_slider">
                                        <div id="slider-tap" onchange="filter()"></div>
                                        <input type="hidden" id="min_value" name="min_price"
                                            value="{{ isset($_GET['min_price']) ? $_GET['min_price'] : '1' }}">
                                        <input type="hidden" id="max_value" name="max_price"
                                            value="{{ isset($_GET['max_price']) ? $_GET['max_price'] : '100000' }}">
                                        <div
                                            class="filter-bar_by_value d-flex align-items-center justify-content-between">
                                            <span id="slider-margin-value-min"></span>
                                            <span id="slider-margin-value-max"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}



                    @if (!areActiveRoutes(['auction.products.category', 'auction_products.all',"auction_collection"], true))
                        <div class="accordion-item">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filter_3" aria-expanded="true" aria-controls="filter_3">
                                Stock
                            </button>
                            <span class="clear_acc" role="button" onclick="clear_filter_by_container('#filter_3')">
                                Clear All
                            </span>
                            <div id="filter_3" class="accordion-collapse collapse show">
                                <div class="accordion-body">
                                    <div class="filter-search-option">
                                        <div class="custom-scroll">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="in_stock"
                                                        name="in_stock" onchange="filter()"
                                                        @checked(isset($in_stock) ? true : false)>
                                                    <label class="form-check-label" for="in_stock">In Stock </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="new_product"
                                                        name="new_product" onchange="filter()"
                                                        @checked(isset($new_product) ? true : false)>
                                                    <label class="form-check-label" for="new_product">New
                                                        Products</label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="on_sale"
                                                        name="on_sale" onchange="filter()"
                                                        @checked(isset($on_sale) ? true : false)>
                                                    <label class="form-check-label" for="on_sale">On Sale</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filter_4" aria-expanded="true" aria-controls="filter_4">
                        Gender
                    </button>
                    <span class="clear_acc" role="button" onclick="clearFilter('gender[]')">Clear All</span>
                    <div id="filter_4" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="male"
                                                class="form-check-input" id="checkBox_gender_male"
                                                onchange="filter()">
                                            <label class="form-check-label"
                                                for="checkBox_gender_male">Menswear</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="female"
                                                class="form-check-input" id="checkBox_gender_female"
                                                onchange="filter()">
                                            <label class="form-check-label"
                                                for="checkBox_gender_female">Womenswear</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="children"
                                                class="form-check-input" id="checkBox_gender_children"
                                                onchange="filter()">
                                            <label class="form-check-label"
                                                for="checkBox_gender_children">Children</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filter_5" aria-expanded="true" aria-controls="filter_5">
                            Category
                        </button>
                        <span class="clear_acc" role="button" onclick="clear_filter_by_container('#filter_5')">Clear
                            All</span>
                        <div id="filter_5" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="filter-search-option">
                                    <div class="custom-scroll">
                                        {{-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
                                        <div class="" id="accordionPanelsStayOpenExample">
                                            @foreach ($categories as $parent_index => $category)
                                                <div class="accordion-item category-item">
                                                    <!-- Parent Categories -->

                                                    {{-- @dd(array_intersect($category->childrenCategories->pluck("id")->toArray(),$category_ids)) --}}
                                                    <div class="accordion-item-inn form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="category_ids[]"
                                                        value="{{ $category->id }}"
                                                            id="aii{{ $parent_index }}"
                                                            {{ isset($category_ids) && in_array($category->id, $category_ids) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="aii{{ $parent_index }}"
                                                            >{{ $category->name }}</label>
                                                    </div>
                                                    <!-- Sub Categories -->
                                                    <div id="panelsStayOpen-{{ $parent_index }}"
                                                        class="accordion-collapse @if (isset($category_ids)  && array_intersect($category->childrenCategories->pluck("id")->toArray(),$category_ids)) d-block @endif"
                                                        aria-labelledby="panelsStayOpen-btn-{{ $parent_index }}">
                                                        <div class="accordion-body">
                                                            @foreach ($category->childrenCategories as $child_index => $subCat)
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input"
                                                                            name="category_ids[]"
                                                                            value="{{ $subCat->id }}"
                                                                            id="checkBox_child_category_{{ $parent_index . $child_index }}"
                                                                            onchange="filter()"
                                                                            {{ isset($category_ids) && in_array($subCat->id, $category_ids) ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="checkBox_child_category_{{ $parent_index . $child_index }}">{{ $subCat->name }}</label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        {{-- +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ --}}
                                        {{-- <div class="ms-2">
                                            @foreach ($categories as $index => $category)
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            name="category_ids[]" value="{{ $category->id }}"
                                                            id="checkBox_child_category_{{ $index }}"
                                                            onchange="filter()"
                                                            {{ isset($category_ids) && in_array($category->id, $category_ids) ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="checkBox_child_category_{{ $index }}">{{ $category->name }}</label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_Brand" aria-expanded="true" aria-controls="collapse_Brand">
                            Brand
                        </button>
                        <span class="clear_acc" role="button"
                            onclick="clear_filter_by_container('#collapse_Brand')">Clear All</span>
                        <div id="collapse_Brand" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="filter-search-option">
                                    <div class="custom-scroll">
                                        @foreach ($brands as $index => $brand)
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        value="{{ $brand->id }}" name="brand_ids[]"
                                                        id="brand_checkbox_{{ $index }}" onchange="filter()"
                                                        @checked(isset($brand_ids) ? in_array($brand->id, $brand_ids) : '')>
                                                    <label class="form-check-label"
                                                        for="brand_checkbox_{{ $index }}">{{ $brand->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (areActiveRoutes(['auction.products.category', 'auction_products.all']))

                    <div class="accordion-item">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse_city" aria-expanded="true" aria-controls="collapse_city">
                            City/Location
                        </button>
                        <span class="clear_acc" role="button"
                            onclick="clear_filter_by_container('#collapse_city')">Clear All</span>
                        <div id="collapse_city" class="accordion-collapse collapse show">
                            <div class="accordion-body">
                                <div class="filter-search-option">
                                    <div class="custom-scroll">
                                        @foreach (getCityWhichAreUsedInProductLocation() as $city)
                                            <div class="form-group">
                                                {{-- check --}}
                                                <div class="form-check">
                                                    <input type="checkbox"
                                                    class="form-check-input"
                                                    name="filterattributes[]"
                                                    value="{{ $city->value }}"
                                                    id="filterattributes{{$city->value}}"
                                                    onchange="filter()"
                                                    @checked(in_array($city->value, request()->filterattributes ?? []))>
                                                    <label class="form-check-label"
                                                    for="filterattributes{{$city->value}}">{{ $city->value }}</label>
                                                </div>
                                                {{-- check --}}
                                                {{-- Select --}}
                                                <div class="shop__all__top__right" style="background-image: none">
                                                    <select class="form-control form-select" name="filterattributes[]">
                                                        <option value="{{$city->value}}">{{$city->value}}</option>
                                                    </select>
                                                </div>
                                                {{-- Select --}}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if (areActiveRoutes(['auction.products.category', 'auction_products.all']))
                        {{-- Iterating over parent category to get sub category --}}
                        @foreach ($categories as $index => $parent_category)
                            {{-- Iterating over subcategories to retrieve their attributes,
                                as only subcategories possess these attributes. --}}
                            @foreach ($parent_category->childrenCategories as $child_index => $category)
                                {{--  The continue statement is used to skip the display of attributes that do not belong to the selected subcategories. --}}
                                @continue(!isset($category_ids) || !in_array($category->id, $category_ids))
                                @foreach ($category->auctionAttributes as $index2 => $attribute)
                                    <div class="accordion-item">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_Attribute{{ $index }}{{ $index2 }}"
                                            aria-expanded="true"
                                            aria-controls="collapse_Attribute{{ $index }}{{ $index2 }}">
                                            {{ $attribute->fields_name }}
                                        </button>
                                        <span class="clear_acc" role="button"
                                            onclick="clear_filter_by_container('#collapse_Attribute{{ $index }}{{ $index2 }}')">Clear
                                            All</span>
                                        <div id="collapse_Attribute{{ $index }}{{ $index2 }}"
                                            class="accordion-collapse collapse show">
                                            <div class="accordion-body">
                                                <div class="filter-search-option">
                                                    <div class="custom-scroll">
                                                        @foreach (explode(',', substr($attribute->dd_value, 0, -1)) as $index3 => $value)
                                                            <div class="ms-2">
                                                                <div class="form-group">
                                                                    <div class="form-check">
                                                                        <input type="checkbox"
                                                                            class="form-check-input"
                                                                            name="filterattributes[]"
                                                                            value="{{ $value }}"
                                                                            id="filterattributes{{ $index }}{{ $index2 }}{{ $index3 }}"
                                                                            onchange="filter()"
                                                                            @checked(in_array($value, request()->filterattributes ?? []))>
                                                                        <label class="form-check-label"
                                                                            for="filterattributes{{ $index }}{{ $index2 }}{{ $index3 }}">{{ $value }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endif

                    {{-- <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapse_Size" aria-expanded="true" aria-controls="collapse_Size">
                        Size
                    </button>
                    <span class="clear_acc" role="button"
                        onclick="clear_filter_by_container('#collapse_Size')">Clear All</span>
                    <div id="collapse_Size" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="84"
                                                name="brand_ids[]" id="brand_checkbox_84" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_84">Ugg </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="51"
                                                name="brand_ids[]" id="brand_checkbox_51" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_51">Valentino
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="85"
                                                name="brand_ids[]" id="brand_checkbox_85" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_85">Van Cleef
                                                &amp; Arpels </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="52"
                                                name="brand_ids[]" id="brand_checkbox_52" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_52">Versace
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                </div>
            </form>
        </div>
    </div>
</div>

@section('scriptjs')
    {{-- <script>
        var sliderTap = document.getElementById('slider-tap');
        var minInput = document.getElementById('min_value');
        var maxInput = document.getElementById('max_value');
        var minValue = parseInt(minInput.value);
        var maxValue = parseInt(maxInput.value);
        if (isNaN(minValue)) {
            minValue = 1; // Default minimum value if NaN
        }
        if (isNaN(maxValue)) {
            maxValue = 100000; // Default maximum value if NaN
        }
        console.log('cons ' + minValue + ' to ' + maxValue);
        noUiSlider.create(sliderTap, {
            start: [minValue, maxValue],
            connect: true,
            range: {
                'min': 1,
                'max': 100000
            }
        });
        sliderTap.noUiSlider.on('update', function(values, handle) {
            var value = parseInt(values[handle]);
            if (handle === 0) {
                minInput.value = value;
                document.getElementById('slider-margin-value-min').textContent = 'Min: $' + value;
            }
            if (handle === 1) {
                maxInput.value = value;
                document.getElementById('slider-margin-value-max').textContent = 'Max: $' + value;
            }
        });
        var timeout = null;
        sliderTap.noUiSlider.on('change', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                filter();
            }, 300);
        });
    </script> --}}
@endsection
