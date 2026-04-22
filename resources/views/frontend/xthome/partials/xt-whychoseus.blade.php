
<!-- cta-section -->
        <section class="cta-section">
            <div class="image-layer" style="background-image: url({{uploaded_asset(get_setting('contact_us_image'))}});"></div>
            <div class="auto-container">
                <div class="cta-inner centred">
                    <div class="pattern-layer">
                        <div class="pattern-1" style="background-image: url({{ static_asset('xt-assets')}}/images/shape/shape-2.png);"></div>
                        <div class="pattern-2" style="background-image: url({{ static_asset('xt-assets')}}/images/shape/shape-3.png);"></div>
                    </div>
                    <h2>{{ get_setting('contact_section_heading') }}</h2>
                    <p>{{ get_setting('contact_section_desc') }}</p>
                    <a href="{{ route('marketplace')}}" class="theme-btn-one">Shop Now<i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </section>
        <!-- cta-section end -->
