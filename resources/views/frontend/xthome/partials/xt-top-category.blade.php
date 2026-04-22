<!-- topcategory-section -->
@if (count($featured_categories) > 0)
    <section class="topcategory-section centred">
        <div class="auto-container">
            <div class="sec-title">
                <h2>Shop By Category</h2>
                <span class="separator"
                    style="background-image: url('{{ static_asset('xt-assets/images/icons/separator-1.png') }}');"></span>
            </div>
            <div class="row clearfix">
                @foreach ($featured_categories->take(4) as $key => $category)
                @php
                    $category_name = $category->getTranslation('name');
                @endphp
                <div class="col-lg-3 col-md-6 col-sm-12 category-block">
                    <div class="category-block-one wow fadeInUp animated animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <figure class="image-box"><img src="{{ isset($category->coverImage->file_name) ? my_asset($category->coverImage->file_name) : static_asset('assets/img/placeholder.jpg') }}" alt="{{ $category_name }}" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"></figure>
                        <h5><a href="{{ route('products.category', $category->slug) }}"> {{ $category_name }}</a></h5>
                    </div>
                </div>
              @endforeach
            </div>
        </div>
    </section>
@endif
<!-- topcategory-section end -->
