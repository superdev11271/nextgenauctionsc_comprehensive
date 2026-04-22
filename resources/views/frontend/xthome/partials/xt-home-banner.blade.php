@php $lang = get_system_language()->code; @endphp
@if (get_setting('home_slider_images', null, $lang) != null && get_setting('show_home_slider', true))
<section class="banner-style-one">
    <div class="pattern-layer" style="background-image: url('{{ static_asset('xt-assets/images/shape/shape-1.png') }}');"></div>
    <div class="banner-carousel owl-theme owl-carousel">
        @php
        $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
        $sliders = get_slider_images($decoded_slider_images);
        $home_slider_links = get_setting('home_slider_links', null, $lang);
        $home_slider_head_one = get_setting('home_slider_head_one', null, $lang);
        $home_slider_head_two = get_setting('home_slider_head_two', null, $lang);
        $home_slider_head_three = get_setting('home_slider_head_three', null, $lang);
        @endphp
        @foreach ($sliders as $key => $slider)
        <div class="slide-item">
            <div class="auto-container">
                <div class="content-inner">
                    <div class="content-box">
                        {!! isset(json_decode($home_slider_head_one, true)[$key]) ? '<h1>' . json_decode($home_slider_head_one, true)[$key] . '</h1>' : '' !!}
                        {!! isset(json_decode($home_slider_head_two, true)[$key]) ? '<h3>' . json_decode($home_slider_head_two, true)[$key] . '</h3>' : '' !!}
                        {!! isset(json_decode($home_slider_head_three, true)[$key]) ? '<p>' . json_decode($home_slider_head_three, true)[$key] . '</p>' : '' !!}
                        @if (isset(json_decode($home_slider_links, true)[$key]))
                        <div class="btn-box">
                            <a href="{!! json_decode($home_slider_links, true)[$key] !!}" class="theme-btn-one">Explore Now<i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                        @endif
                    </div>
                    <figure class="image-box image-1"><img src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}" alt="{{ env('APP_NAME') }} xthome" onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';" alt="">
                    </figure>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
