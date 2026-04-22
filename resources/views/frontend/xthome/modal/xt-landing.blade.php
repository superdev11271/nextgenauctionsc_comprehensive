@php
    $header_logo = get_setting('header_logo');
@endphp
<div class="modal fade landing-model" id="landing_model" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl m-0">
            <div class="modal-content p-0 bg-black">
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal" aria-label="Close">X</button>
                <div class="modal-body p-0">
                    <section class="m-100">
                        <div class="auto-container">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="text-center">
                                        <div class="landing-model-logo">
                                            @if ($header_logo != null)
                                                <figure class="logo-box"><a href="{{ route('home') }}"><img
                                                            src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"></a></figure>
                                            @else
                                            <img src="{{ static_asset('xt-assets/images/nextgenLogo.png') }}" alt="">
                                                <figure class="logo-box"><a href="{{ route('home') }}"><img 
                                                            src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"></a>
                                                </figure>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-12 col-lg-12 col-md-12 col-ms-12 mx-auto">
                                    <div class="d-flex flex-wrap gap-5">                                       
                                            <div class="bg-light-grey-landing" id="auctionButton">
                                                <p class="landing-head">Auction</p>
                                            </div>        
                                            <div class="bg-light-grey-landing" id="marketplaceButton">
                                                <p class="landing-head">Marketplace</p>
                                            </div>                     
                                        </div>
                                    </div>
                                </div>                
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>