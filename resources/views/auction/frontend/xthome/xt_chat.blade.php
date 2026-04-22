@extends('frontend.layouts.xt-app')
@push('css')
    <link href="{{ static_asset('xt-assets') }}/css/account-details.css" rel="stylesheet">
    <link href="{{ static_asset('xt-assets') }}/css/chat.css" rel="stylesheet">
@endpush
@section('content')
    <section class="shop-section account-details pt-5">
        <div class="auto-container">
            <div class="row">
                @include('frontend.xthome.partials.xt-customer-sidebar')
                <div class="col-lg-8 col-xxl-9">
                    <div class="card-header py-3">
                        <h5 class="m-0">Negotiate with seller</h5>
                    </div>

                    <div class="card-body light-dark-bg px-4 p-2">
                        <div class="chat-app mt-3">
                            <div class="chat" style="width: 100%">
                                <div class="chat-header clearfix">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="chat-about">
                                                <a href="{{ route('auction-product', $bid->product->slug) }}"
                                                    target="_blank"
                                                    class="d-flex align-items-center">
                                                    <img class="lazyload m-2 rounded-0"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ uploaded_asset($bid->product->thumbnail_img) }}"
                                                        alt="{{ $bid->product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <span class=" ml-1">Product:
                                                        {{ $bid->product->getTranslation('name') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex flex-column justify-content-between align-items-end">
                                            <h3 class="h6">Current Bid: <span id="current_bid">${{ $bid->product->bids()->max('amount') }}</span></h3>
                                            <h3 class="h6">My Bid: <span id="my_bid">${{ $bid->amount }}</span></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-history">
                                    <ul class="m-b-0" id="msg_container">
                                        @foreach ($chatHistory as $chat)
                                            @if ($chat->sender == auth()->id())
                                                <li class="clearfix">
                                                    <div class="message-data text-right">
                                                        <span
                                                            class="message-data-time">{{ $chat->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="message other-message float-right">
                                                        {{ $chat->tamplate?->message }}
                                                        {{ $chat->amount ? 'Bid Amount:' . $chat->amount : '' }}</div>
                                                </li>
                                            @else
                                                <li class="clearfix">
                                                    <div class="message-data">
                                                        <span
                                                            class="message-data-time">{{ $chat->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <div class="message my-message">
                                                        {{ $chat->tamplate?->message }} Bid Amount:{{ $chat->amount }}
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </div>
                                <div class="chat-message clearfix">
                                    <div class="input-group mb-3">
                                        <button type="button" onclick="makeOffer()" class="w-100 theme-btn-card px-4"
                                            id="basic-addon2">Make Offer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <div class="modal fade" id="accept_bid_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-modal="true">
        <div class="modal-dialog modal-dialog-zoom" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-600">Make you best offer</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="chatform">
                    <input type="hidden" name="bid_id" value="{{ $bid->id }}">
                    <div class="modal-body">
                        <div class="flex-wrap">
                            <div class="signin-form">
                                <div class="error alert alert-danger d-none" id="form-errors"></div>
                                <div class="form-group">

                                    <div class="position-relative mb-4">
                                        <label for="message" class="form-label">Message</label>
                                        <select class="form-select form-select-lg theam-select" name="chat_tamplate_id"
                                            id="message" required>
                                            <option value="" selected disabled>Select Message</option>
                                            @foreach ($formats as $format)
                                                <option value="{{ $format->id }}"
                                                    data-with_amount="{{ $format->with_amount }}">
                                                    {{ $format->message }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div id="amount_section" style="display: none">
                                        <div class="mb-1"><label for="customRange1" class="form-label">Select
                                                amount</label>
                                        </div>
                                        <div class="position-relative mb-4">
                                            <input type="range" id="range" value="{{ $bid->amount }}"
                                                min="{{ $bid->amount }}" max="{{ $bid->amount * 2 }}" step="5"
                                                name="amount" />
                                        </div>
                                        <div class="mt-2 text-center">
                                            <div class="mt-1">Are you sure you want to bid</div>
                                            <div class="d-flex gap-2 justify-content-center mt-2 align-items-center">
                                                <div>Price</div>
                                                <div class="fs-16 fw-700">
                                                    <h5 class="text-sub">$<span
                                                            id="rangeValue">{{ $bid->amount }}</span>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="d-flex flax-wrap gap-3 justify-content-center mt-4">
                                        <button id="login-btn1" type="button" class="input-group-text theme-btn-card"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="input-group-text theme-btn-card" id="accept-link"
                                            onclick="validateAndSendMsg()">Accept</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scriptjs')
    <script type="text/javascript">
        function showAuctionAddToCartModal(id) {
            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }
            $('#addToCart-modal-body').html(null);
            $('#addToCart').modal('show');
            $('.c-preloader').show();
            $.post('{{ route('auction.cart.showCartModal') }}', {
                _token: AIZ.data.csrf,
                id: id
            }, function(data) {
                $('.c-preloader').hide();
                $('#addToCart-modal-body').html(data);
                AIZ.plugins.slickCarousel();
                AIZ.plugins.zoom();
                AIZ.extra.plusMinus();
                getVariantPrice();
            });
        }
    </script>

    <script>
        // let FrontChatModule = function() {
        // }
                // Check new messages via AJAX
        let lastBid = {{ $bid->amount }};
        let lastmsgId = {{ $chatHistory->last()?->id ?? 0 }};

        $("#message").on("change", function() {
            var selectedOption = $(this).find('option:selected');
            var isAmountRequired = selectedOption.data('with_amount');

            if (isAmountRequired == 1) {
                $("#amount_section").show()
            } else {
                $("#amount_section").hide()
            }
        })

        $('#range').on("input", function() {
            if (lastBid > $(this).val()) {
                $(this).val(lastBid)
            }
            $('#rangeValue').text($(this).val())
            $("#amount").val($(this).val())

        })

        let format = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2,
        });

        function makeOffer() {
            $("#accept_bid_modal").modal("show");
        }



        if (lastmsgId) {
            setInterval(() => {
                refresh()
            }, 3000);
        }

        function refresh() {
            let url = '{{ route('chat.updates', ':id') }}';
            let finalUrl = url.replace(":id", lastmsgId)
            $.ajax({
                url: finalUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if(response.refresh_required) location.reload()
                    $("#current_bid").text(format.format(response.current_bid))
                    $("#my_bid").text(format.format(response.my_bid))
                    response.data.forEach(chat => {
                        appendMsg(
                            `${chat.tamplate?.message}${chat.amount?"Bid Amount: "+chat.amount: ""}`,
                            "just now", 2)
                        lastmsgId = chat.id
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        function scrollToBottom() {
            var chatMessages = $('.chat-history');
            var scrollHeight = chatMessages[0].scrollHeight;
            chatMessages.scrollTop(scrollHeight);
        }


        function appendMsg(msg, time = 'just now', from = 1) {
            // from: 1=send 2=receive
            let container = $("#msg_container");
            let newmsg = `<li class="clearfix">
                <div class="${from==1?'message-data text-right':'message-data'}">
                <span class="message-data-time">${time}</span>
                </div>
                <div class="${from==1?'message other-message float-right':'message my-message'}">
                ${msg}</div>
                </li>`;
            container.append(newmsg)
            scrollToBottom();
        }

        function validateAndSendMsg() {
            var form = $('#chatform');
            if (form[0].checkValidity()) {
                sendMsg();
            } else {
                form[0].reportValidity();
            }
        }

        function sendMsg() {
            var form = $('#chatform');
            let formData = form.serialize()
            $.ajax({
                url: '{{ route('customer.chat.store') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#accept_bid_modal").modal("hide");
                    $("#amount").val("")
                    lastmsgId = response.data.id
                    appendMsg(`${response.data.tamplate.message} ${response.data.amount?"Bid Amount"+response.data.amount: ""}`)
                    form[0].reset();
                    $("#amount_section").hide()

                },
                error: function(response) {
                    if (response && response.responseJSON && response.responseJSON.errors) {
                        let errors = response.responseJSON.errors;
                        showValidationError("#form-errors", errors);
                    }
                    if (response && response.responseJSON && response.responseJSON.error) {
                        let error = response.responseJSON.error;
                        $("#form-errors").removeClass("d-none").html(`<ul><li>${error}</li></ul>`);
                    }
                }
            });
        }

        $(document).ready(function() {
            scrollToBottom();
        });

    </script>
@endsection
