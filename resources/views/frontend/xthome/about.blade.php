@extends('frontend.layouts.xt-app')

@push('css')
    <link href="{{ static_asset('xt-assets/css/about.css') }}" rel="stylesheet">

    <style>
        .about-breadcrumb-bg {
            background-image: url('{{ uploaded_asset(get_setting("about_heading_image")) }}');
            background-repeat: no-repeat;
            background-size: cover;
            height: 340px;
            display: flex;
            align-items: center;
        }
    </style>
@endpush

@section('content')
     <!-- banner-section -->
     <div class="shopping-cart about-us">
      <section class="breadcrumb__area separator  breadcrumb__pt-310 include-bg p-relative about-breadcrumb-bg text-center d-flex">
        <div class="auto-container">
            <div class="row">
              <div class="col-md-12">
                  <div class="breadcrumb__content py-5 p-relative z-index-1">
                    <h3 class="breadcrumb__title">About Us</h3>                                                   
                  </div>
              </div>                    
            </div>
        </div>
      </section>
    </div>

   <!-- account details -->
   <div class="aboutUsMainDiv pt-33">
      <div class="auto-container">
         <div class="row service-pt-pb pb-0 ac-border-bottom aos-init aos-animate" data-aos="fade-up">
            <div class="col-xl-12 col-lg-12">
               <div class="sd-service-details pb-5">
                  <h2 class="tp-title-lg text-center mb-1">{!! get_setting('about_title')!!}</h2>
                  <p class="pb-15 text-center">{!! get_setting('about_description') !!}</p>
              </div>
            </div>                 
         </div>
      
         @php          
            $aboutSections = json_decode(get_setting('about_sections'), true);
            $aboutdata = group_about_sections($aboutSections);
         @endphp
         @foreach($aboutdata as $index => $section)
            @if($index % 2  == 0)
            <div class="d-flex flex-wrap justify-content-between pb-86">
               <div class="about-left-content text-center"><img src="{{uploaded_asset($section['about_image'] ?? '')}}" alt=""></div>
               <div class="right-content pl-110">
                     <h5 class="tp-title-sm">{!! $section['title'] ?? '' !!}</h5>
                     <p class="second-head">{!! $section['heading']?? '' !!}</p>
                     <p class="pb-15">{!! $section['aboutus_content']?? '' !!}</p>
               </div>
            </div>
            @else
            <div class="d-flex flex-wrap justify-content-between pb-86">
               <div class="right-content pr-110">
                     <h5 class="tp-title-sm">{!! $section['title'] ?? '' !!}</h5>
                     <p class="second-head">{!! $section['heading']?? '' !!}</p>
                     <p class="pb-15">{!! $section['aboutus_content'] !!}</p>
               </div>    
               <div class="about-left-content"><img src="{{uploaded_asset($section['about_image'] ?? '')}}" alt=""></div>     
            </div>
            @endif
          @endforeach
      </div>
   </div>
@endsection