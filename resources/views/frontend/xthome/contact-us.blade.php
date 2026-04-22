@extends('frontend.layouts.xt-app')

@push('css')
<link href="{{static_asset('xt-assets/css/contact.css')}}" rel="stylesheet">
@endpush

@section('content')
    <!-- banner-section -->
    <div class="shopping-cart">
        <section class="breadcrumb__area separator  breadcrumb__pt-310 include-bg p-relative">
            <div class="auto-container">
                <div class="row">
                    <div class="col-md-6">
                    <div class="breadcrumb__content p-relative z-index-1">
                        <h3 class="breadcrumb__title">Contact Us</h3>                                              
                    </div>
                    </div>                    
                </div>
            </div>
        </section>
    </div>
        <!-- banner-section end -->


    <div class='auto-container'>
        <div class="row">
            <div class="col-xl-6 col-lg-6 ">
                <div class="tp-contct-wrapper">
                    <div class="tp-contact-thumb mb-60">
                        {{-- @dd(get_setting('contact_image')); --}}
                        {{-- <img src="{{static_asset('xt-assets/images/contact-1.jpg')}}" alt="" /> --}}
                        <img src="{{uploaded_asset(get_setting('contact_image'))}}" alt="{{ translate('Contact us image') }}" />

                    </div>
                    <div class="tp-contact-info mb-40">
                        <h4 class="contact-title">Mail Address</h4>
                        <span>
                            <a href="mailto:{{ get_setting('header_email')}}">{{ get_setting('header_email')}}</a>
                        </span>
                    </div>
                    <div class="tp-contact-info mb-40">
                        <h4 class="contact-title">Phone Number</h4>
                        <span>
                            <a href="tel:{{ get_setting('helpline_number')}}">{{ get_setting('helpline_number')}}</a>
                        </span>
                    </div>
                    <div class="tp-contact-info mb-5">
                        <h4 class="contact-title">Address line</h4>
                        <span>
                            <a href="">{{ get_setting('contact_address')}}</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6">
                <div class="tpcontact">                       
                    <div class="tpcontact__form tpcontact__form-3">
                        <form action="{{ route('send-enquiry') }}" method="POST" id="contact-us">
                            @csrf
                            <div class="mb-30">
                                <input name="name" type="text" @if(Auth::check())value="{{Auth::user()->name}}" @endif placeholder="Enter your Name *" required/>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-30">
                                <input name="email" type="email" autocomplete="email" @if(Auth::check())value="{{Auth::user()->email}}" @endif placeholder="Enter your Mail *" class="@error('email') is-invalid @enderror" required/>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-30">
                                <textarea name="message" placeholder="Enter your Massage *" required></textarea>
                                @error('message')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-30">
                                <button type="submit" class="theme-btn-one">Send Message<i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



@section('script')
        <script type="text/javascript">
    $(document).ready(function () {
        if (!(window.jQuery && $.validator && $.fn && $.fn.validate)) {
            return;
        }

        $.validator.addMethod('customWhitespaceValidation', function (value, element) {
        return this.optional(element) || /\S/.test(value);
        }, 'Whitespaces are not allowed.');
        $("#contact-us").validate({
            rules: {
                name: {
                    required: true,
                    customWhitespaceValidation: true
                },
                email: {
                    required: true,
                    customWhitespaceValidation: true
                },
                message: {
                    required: true,
                    customWhitespaceValidation: true
                }
            },
            messages: {
                name: {
                required: "<span class='text-danger'>Please enter your name</span>",
                customWhitespaceValidation: "<span class='text-danger'>Please enter your name</span>",
                },
                email: {
                required: "<span class='text-danger'>Please enter email</span>",
                customWhitespaceValidation: "<span class='text-danger'>Please enter email</span>",
                },
                message: {
                required: "<span class='text-danger'>Please enter message</span>",
                customWhitespaceValidation: "<span class='text-danger'>Please enter message</span>",
                }
            },
            tooltip_options: {
                name: {
                    placement: 'top',
                    html: true
                },
                email: {
                    placement: 'top',
                    html: true
                },
                message: {
                    placement: 'top',
                    html: true
                }          
            }
        });

    });
</script>
@endsection