<div class="offcanvas offcanvas-start shopFilterbar-offcanvas" tabindex="-1" id="shopFilterbar" aria-labelledby="offcanvasExampleLabel" aria-modal="true" role="dialog">
    <div class="offcanvas-header">
        <h3 class="offcanvas-title" id="offcanvasExampleLabel">FILTER</h3>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="filter-area " id="flterSideBar">
            <div class="filter-body">
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filter_1" aria-expanded="true" aria-controls="filter_1">
                        Current Filter
                    </button>
                    <span onclick="clearAllFilter()" class="clear_acc" style="cursor: pointer;">Clear All</span>
                    <div id="filter_1" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <ul class="filter-search-currents">
                                <li>
                                    <p>Gender</p>
                                    <span class="clear-single" onclick="clearFilter('gender[]')"><img src="images/icons/close.svg" alt="{{ translate('Clear filter') }}"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filter_2" aria-expanded="true" aria-controls="filter_2">
                        Price
                    </button>
                    <div id="filter_2" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="sliders_control">
                                    <input id="fromSlider" type="range" name="min_price" value="0" min="0" max="424353.00" onchange="filter()">
                                    <input id="toSlider" type="range" name="max_price" value="424353.00" min="0" max="424353.00" onchange="filter()">
                                </div>
                                <div>
                                    <div class="filter_range_value d-flex align-items-center">
                                        <div class="position-relative"><input type="number" id="fromInput" class="rang-box" value="0" min="0" max="424353.00"><span class="cr_icon">£</span></div>
                                        <div class="position-relative"><input type="number" id="toInput" class="rang-box" value="424353.00" min="0" max="424353.00"><span class="cr_icon">£</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filter_3" aria-expanded="true" aria-controls="filter_3">
                        Stock
                    </button>
                    <span class="clear_acc" role="button" onclick="clearFilter('in_stock');clearFilter('new_product');clearFilter('on_sale')">Clear All</span>
                    <div id="filter_3" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="in_stock" name="in_stock" onchange="filter()">
                                            <label class="form-check-label" for="in_stock">In Stock </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="new_product" name="new_product" onchange="filter()">
                                            <label class="form-check-label" for="new_product">New Products</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="on_sale" name="on_sale" onchange="filter()">
                                            <label class="form-check-label" for="on_sale">On Sale</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filter_4" aria-expanded="true" aria-controls="filter_4">
                        Gender
                    </button>
                    <span class="clear_acc" role="button" onclick="clearFilter('gender[]')">Clear All</span>
                    <div id="filter_4" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="male" class="form-check-input" id="checkBox_gender_male" onchange="filter()" checked="">
                                            <label class="form-check-label" for="checkBox_gender_male">Menswear</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="female" class="form-check-input" id="checkBox_gender_female" onchange="filter()" checked="">
                                            <label class="form-check-label" for="checkBox_gender_female">Womenswear</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" name="gender[]" value="children" class="form-check-input" id="checkBox_gender_children" onchange="filter()" checked="">
                                            <label class="form-check-label" for="checkBox_gender_children">Children</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#filter_5" aria-expanded="true" aria-controls="filter_5">
                        Category
                    </button>
                    <span class="clear_acc" role="button" onclick="clearFilter('category_ids[]')">Clear All</span>
                    <div id="filter_5" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="my-2">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <span class="text-sub">Menswear</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ms-2">
                                        <div class="form-group">
                                            <div class="form-check">
                                                <label class="form-check-label" for="checkBox_child_category_58">Accessories </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="category_ids[]" value="63" id="checkBox_child_category_63" onchange="filter()">
                                                <label class="form-check-label" for="checkBox_child_category_63">Blazers &amp; Suits </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="accordion-item">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_Size" aria-expanded="true" aria-controls="collapse_Size">
                        Size
                    </button>
                    <span class="clear_acc" role="button" onclick="clearFilter('selected_attribute_values[]', 'collapse_Size')">Clear All</span>
                    <div id="collapse_Size" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="filter-search-option">
                                <div class="custom-scroll">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="84" name="brand_ids[]" id="brand_checkbox_84" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_84">Ugg </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="51" name="brand_ids[]" id="brand_checkbox_51" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_51">Valentino </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="85" name="brand_ids[]" id="brand_checkbox_85" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_85">Van Cleef &amp; Arpels </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" value="52" name="brand_ids[]" id="brand_checkbox_52" onchange="filter()">
                                            <label class="form-check-label" for="brand_checkbox_52">Versace </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>