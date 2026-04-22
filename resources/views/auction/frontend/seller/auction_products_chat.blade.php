{{-- @dd(auth()->user()->user_type) --}}
{{-- @extends((auth()->user()->user_type != 'seller' || auth()->user()->user_type != 'customer') ? 'backend.layouts.app' : 'seller.layouts.app') --}}
@extends(auth()->user()->user_type != 'seller' && auth()->user()->user_type != 'customer' ? 'backend.layouts.app' : 'seller.layouts.app')
@section('css')
    <style>
        .message-area {
            overflow: hidden;
            padding: 14px;
            background: #f5f5f5;

            .chat-area {
                position: relative;
                width: 100%;
                background-color: #fff;
                border-radius: 0.3rem;
                height: 80vh;
                overflow: hidden;
                min-height: calc(100% - 1rem);
            }

            .pt-10 {
                padding-top: 10px !important;
            }

            .chatlist {
                outline: 0;
                height: 100%;
                overflow: hidden;
                width: 300px;
                float: left;
                padding: 15px;
            }

            .chat-area .modal-content {
                border: none;
                border-radius: 0;
                outline: 0;
                height: 100%;
                max-height: max-content !important;
            }

            .modal-content .modal-body {
                max-height: 90vh;
            }

            .chat-area .modal-dialog-scrollable {
                height: 100% !important;
                max-height: max-content !important;
            }


            .chatbox {
                width: auto;
                overflow: hidden;
                height: 100%;
                border-left: 1px solid #ccc;
            }

            .chatbox .modal-dialog,
            .chatlist .modal-dialog {
                max-width: 100%;
                margin: 0;
            }

            .msg-search {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .chat-area .form-control {
                display: block;
                width: 80%;
                padding: 0.375rem 0.75rem;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                color: #222;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ccc;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                border-radius: 0.25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .chat-area .form-control:focus {
                outline: 0;
                box-shadow: inherit;
            }

            a.add img {
                height: 36px;
            }

            .chat-area .nav-tabs {
                border-bottom: 1px solid #dee2e6;
                align-items: center;
                justify-content: space-between;
                flex-wrap: inherit;
            }

            .chat-area .nav-tabs .nav-item {
                width: 100%;
            }

            .chat-area .nav-tabs .nav-link {
                width: 100%;
                color: #180660;
                font-size: 14px;
                font-weight: 500;
                line-height: 1.5;
                text-transform: capitalize;
                margin-top: 5px;
                margin-bottom: -1px;
                background: 0 0;
                border: 1px solid transparent;
                border-top-left-radius: 0.25rem;
                border-top-right-radius: 0.25rem;
            }

            .chat-area .nav-tabs .nav-item.show .nav-link,
            .chat-area .nav-tabs .nav-link.active {
                color: #222;
                background-color: #fff;
                border-color: transparent transparent #000;
            }

            .chat-area .nav-tabs .nav-link:focus,
            .chat-area .nav-tabs .nav-link:hover {
                border-color: transparent transparent #000;
                isolation: isolate;
            }

            .chat-list h3 {
                color: #222;
                font-size: 16px;
                font-weight: 500;
                line-height: 1.5;
                text-transform: capitalize;
                margin-bottom: 0;
            }

            .chat-list p {
                color: #343434;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                text-transform: capitalize;
                margin-bottom: 0;
            }

            .chat-list a.d-flex {
                margin-bottom: 15px;
                position: relative;
                text-decoration: none;
            }

            .chat-list .active {
                display: block;
                content: '';
                clear: both;
                position: absolute;
                bottom: 3px;
                left: 34px;
                height: 12px;
                width: 12px;
                background: #00DB75;
                border-radius: 50%;
                border: 2px solid #fff;
            }

            .msg-head h3 {
                color: #222;
                font-size: 18px;
                font-weight: 600;
                line-height: 1.5;
                margin-bottom: 0;
            }

            .msg-head p {
                color: #343434;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                text-transform: capitalize;
                margin-bottom: 0;
            }

            .msg-head {
                padding: 15px;
                border-bottom: 1px solid #ccc;
            }

            .moreoption {
                display: flex;
                align-items: center;
                justify-content: end;
            }

            .moreoption .navbar {
                padding: 0;
            }

            .moreoption li .nav-link {
                color: #222;
                font-size: 16px;
            }

            .moreoption .dropdown-toggle::after {
                display: none;
            }

            .moreoption .dropdown-menu[data-popper] {
                top: 100%;
                left: auto;
                right: 0;
                margin-top: 0.125rem;
            }

            .msg-body ul {
                overflow: hidden;
            }

            .msg-body ul li {
                list-style: none;
                margin: 15px 0;
            }

            .msg-body ul li.sender {
                display: block;
                width: 100%;
                position: relative;
            }

            .msg-body ul li.sender:before {
                display: block;
                clear: both;
                content: '';
                position: absolute;
                top: -6px;
                left: -7px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 12px 15px 12px;
                border-color: transparent transparent #f5f5f5 transparent;
                -webkit-transform: rotate(-37deg);
                -ms-transform: rotate(-37deg);
                transform: rotate(-37deg);
            }

            .msg-body ul li.sender p {
                color: #000;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 400;
                padding: 15px;
                background: #f5f5f5;
                display: inline-block;
                border-bottom-left-radius: 10px;
                border-top-right-radius: 10px;
                border-bottom-right-radius: 10px;
                margin-bottom: 0;
            }

            .msg-body ul li.sender p b {
                display: block;
                color: #180660;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 500;
            }

            .msg-body ul li.repaly {
                display: block;
                width: 100%;
                text-align: right;
                position: relative;
            }

            .msg-body ul li.repaly:before {
                display: block;
                clear: both;
                content: '';
                position: absolute;
                bottom: 15px;
                right: -7px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 12px 15px 12px;
                border-color: transparent transparent #4b7bec transparent;
                -webkit-transform: rotate(37deg);
                -ms-transform: rotate(37deg);
                transform: rotate(37deg);
            }

            .msg-body ul li.repaly p {
                color: #fff;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 400;
                padding: 15px;
                background: #4b7bec;
                display: inline-block;
                border-top-left-radius: 10px;
                border-top-right-radius: 10px;
                border-bottom-left-radius: 10px;
                margin-bottom: 0;
            }

            .msg-body ul li.repaly p b {
                display: block;
                color: #061061;
                font-size: 14px;
                line-height: 1.5;
                font-weight: 500;
            }

            .msg-body ul li.repaly:after {
                display: block;
                content: '';
                clear: both;
            }

            .time {
                display: block;
                color: #000;
                font-size: 12px;
                line-height: 1.5;
                font-weight: 400;
            }

            li.repaly .time {
                margin-right: 20px;
            }

            .divider {
                position: relative;
                z-index: 1;
                text-align: center;
            }

            .msg-body h6 {
                text-align: center;
                font-weight: normal;
                font-size: 14px;
                line-height: 1.5;
                color: #222;
                background: #fff;
                display: inline-block;
                padding: 0 5px;
                margin-bottom: 0;
            }

            .divider:after {
                display: block;
                content: '';
                clear: both;
                position: absolute;
                top: 12px;
                left: 0;
                border-top: 1px solid #EBEBEB;
                width: 100%;
                height: 100%;
                z-index: -1;
            }

            .send-box {
                padding: 15px;
                border-top: 1px solid #ccc;
            }

            .send-box form {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            /* .send-box select.form-control{width: auto;} */
            .send-box .form-control {
                display: block;
                width: 85%;
                padding: 0.375rem 0.75rem;
                font-size: 14px;
                font-weight: 400;
                line-height: 1.5;
                color: #222;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ccc;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                border-radius: 0.25rem;
                transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            }

            .send-box button {
                border: none;
                background: #3867d6;
                padding: 0.375rem 5px;
                color: #fff;
                border-radius: 0.25rem;
                font-size: 14px;
                font-weight: 400;
                width: 24%;
                margin-left: 1%;
            }

            .send-box button i {
                margin-right: 5px;
            }

            .send-btns .button-wrapper {
                position: relative;
                width: 125px;
                height: auto;
                text-align: left;
                margin: 0 auto;
                display: block;
                background: #F6F7FA;
                border-radius: 3px;
                padding: 5px 15px;
                float: left;
                margin-right: 5px;
                margin-bottom: 5px;
                overflow: hidden;
            }

            .send-btns .button-wrapper span.label {
                position: relative;
                z-index: 1;
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
                -webkit-box-align: center;
                -ms-flex-align: center;
                align-items: center;
                width: 100%;
                cursor: pointer;
                color: #343945;
                font-weight: 400;
                text-transform: capitalize;
                font-size: 13px;
            }

            #upload {
                display: inline-block;
                position: absolute;
                z-index: 1;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                opacity: 0;
                cursor: pointer;
            }

            .send-btns .attach .form-control {
                display: inline-block;
                width: 120px;
                height: auto;
                padding: 5px 8px;
                font-size: 13px;
                font-weight: 400;
                line-height: 1.5;
                color: #343945;
                background-color: #F6F7FA;
                background-clip: padding-box;
                border: 1px solid #F6F7FA;
                border-radius: 3px;
                margin-bottom: 5px;
            }

            .send-btns .button-wrapper span.label img {
                margin-right: 5px;
            }

            .button-wrapper {
                position: relative;
                width: 100px;
                height: 100px;
                text-align: center;
                margin: 0 auto;
            }

            button:focus {
                outline: 0;
            }

            .add-apoint {
                display: inline-block;
                margin-left: 5px;
            }

            .add-apoint a {
                text-decoration: none;
                background: #F6F7FA;
                border-radius: 8px;
                padding: 8px 8px;
                font-size: 13px;
                font-weight: 400;
                line-height: 1.2;
                color: #343945;
            }

            .add-apoint a svg {
                margin-right: 5px;
            }

            .chat-icon {
                display: none;
            }

            .closess i {
                display: none;
            }

        }



        @media (max-width: 767px) {
            .message-area {
                .chat-icon {
                    display: block;
                    margin-right: 5px;
                }

                .chatlist {
                    width: 100%;
                }

                .chatbox {
                    width: 100%;
                    position: absolute;
                    left: 1000px;
                    right: 0;
                    background: #fff;
                    transition: all 0.5s ease;
                    border-left: none;
                }

                .showbox {
                    left: 0 !important;
                    transition: all 0.5s ease;
                }

                .msg-head h3 {
                    font-size: 14px;
                }

                .msg-head p {
                    font-size: 12px;
                }

                .msg-head .flex-shrink-0 img {
                    height: 30px;
                }

                .send-box button {
                    width: 28%;
                }

                .send-box .form-control {
                    width: 70%;
                }

                .chat-list h3 {
                    font-size: 14px;
                }

                .chat-list p {
                    font-size: 12px;
                }

                .msg-body ul li.sender p {
                    font-size: 13px;
                    padding: 8px;
                    border-bottom-left-radius: 6px;
                    border-top-right-radius: 6px;
                    border-bottom-right-radius: 6px;
                }

                .msg-body ul li.repaly p {
                    font-size: 13px;
                    padding: 8px;
                    border-top-left-radius: 6px;
                    border-top-right-radius: 6px;
                    border-bottom-left-radius: 6px;
                }
            }
        }
    </style>
@endsection
@section(auth()->user()->user_type != 'seller' && auth()->user()->user_type != 'customer' ? 'content' : 'panel_content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-auto">
                <h3 class="h3">{{ translate('Negotiation Secion for Product: ') . $product->name }}</h3>
            </div>
        </div>
    </div>
    <br>

    <!-- char-area -->
    <section class="message-area">
        <div class="row">
            <div class="col-12">
                <div class="chat-area">
                    <!-- chatlist -->
                    <div class="chatlist">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="chat-header">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="Open-tab" data-toggle="tab"
                                                data-target="#Open" type="button" role="tab" aria-controls="Open"
                                                aria-selected="true">Offers</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="Closed-tab" data-toggle="tab" data-target="#Closed"
                                                type="button" role="tab" aria-controls="Closed"
                                                aria-selected="false">All Bidders</button>

                                        </li>
                                    </ul>
                                </div>

                                <div class="modal-body">
                                    <!-- chat-list -->
                                    <div class="chat-lists">
                                        <div class="tab-content" id="myTabContent">
                                            <div class="tab-pane fade show active" id="Open" role="tabpanel"
                                                aria-labelledby="Open-tab">
                                                <!-- chat-list -->
                                                <div class="chat-list">
                                                    @foreach ($bids as $bid)
                                                        @continue(!$bid->chats || $bid->status != 'open' || $bid->notified == 0)
                                                        <a href="javascript:void(0);" id="chatperson{{ $bid->id }}"
                                                            class="d-flex align-items-center chatperson @if ($currentbid->id == $bid->id) bg-soft-secondary @endif"
                                                            onclick="loadCaht({{ $bid->id }})">
                                                            <div class="flex-shrink-0">
                                                                <img class="img-fluid w-35px rounded-circle"
                                                                    src="{{ uploaded_asset($bid->user->avatar_original) }}"
                                                                    alt="user img">
                                                            </div>
                                                            <div class="flex-grow-1 ml-3">
                                                                <span>
                                                                    <h3>{{ $bid->user->name }}</h3>
                                                                    <p class="text-success fw-600 d-inline">Bid:
                                                                    <span
                                                                            id="bid_amount{{ $bid->id }}">{{ currency_format($bid->amount) }}</span>
                                                                    </p>
                                                                </span>

                                                                @php
                                                                    $chatCount = $bid->getUnviewdMsgCount(
                                                                        $bid->user_id,
                                                                    );
                                                                @endphp

                                                                <span class="badge bg-info text-white absolute-top-right"
                                                                    id="msg_badge{{ $bid->id }}"
                                                                    style="display: {{ $chatCount ? '' : 'none' }}">
                                                                    {{ $chatCount }}
                                                                </span>

                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="Closed" role="tabpanel"
                                                aria-labelledby="Closed-tab">
                                                <!-- chat-list -->
                                                <div class="chat-list">
                                                    @foreach ($bids as $bid)
                                                        @continue($bid->chats)
                                                        <a href="javascript:void(0);" id="chatperson{{ $bid->id }}"
                                                            class="d-flex align-items-center chatperson @if ($currentbid->id == $bid->id) bg-soft-secondary @endif"
                                                            onclick="loadCaht({{ $bid->id }})">
                                                            <div class="flex-shrink-0">
                                                                <img class="img-fluid w-35px rounded-circle"
                                                                    src="{{ uploaded_asset($bid->user->avatar_original) }}"
                                                                    alt="user img">
                                                            </div>
                                                            <div class="flex-grow-1 ml-3">
                                                                <h3>{{ $bid->user->name }}</h3>
                                                                <p class="text-success fw-600">Bid:
                                                                    {{ currency_format($bid->amount) }}</p>
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                                <!-- chat-list -->
                                            </div>
                                        </div>

                                    </div>
                                    <!-- chat-list -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- chatlist -->

                    <!-- chatbox -->
                    <div class="chatbox">
                        <div class="modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="msg-head">
                                    <div class="row position-relative">
                                        <div class="col-8" id="chat_header">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img class="img-fluid  w-35px rounded-circle"
                                                        src="{{ uploaded_asset($bid->user->avatar_original) }}"
                                                        alt="user img">
                                                </div>
                                                <div class="flex-grow-1 ml-3">
                                                    <h3>{{ $currentbid->user->name }}</h3>
                                                    <p class="text-success fw-600">Bid:
                                                        {{ currency_format($currentbid->amount) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4 ">
                                            <div class="d-flex justify-content-end">
                                                <a href="#"
                                                    class="btn-soft-success pt-10 btn-icon btn-circle btn-sm text-center d-flex justify-content-center align-content-center accept-bid"
                                                    onclick="acceptOffer({{ $bid->id }})"
                                                    title="{{ translate('Accept Offer') }}">
                                                    <i class="las la-check"></i>
                                                </a>

                                                {{-- <a href="#"
                                                    class="btn-soft-danger pt-10 btn-icon btn-circle btn-sm text-center d-flex justify-content-center align-content-center reject-bid ml-2"
                                                    onclick="rejectOffer({{ $bid->id }})"
                                                    title="{{ translate('Close Bid') }}">
                                                    <i class="las la-times"></i>
                                                </a> --}}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="modal-body" id="scrollbody">
                                    <div class="msg-body">
                                        <ul id="msg_container">
                                            @foreach ($chatHistory as $key => $chat)
                                                @php $msgTyep = auth()->id() == $chat->sender ? 'repaly' : 'sender' @endphp
                                                <li class='{{ $msgTyep }}'>
                                                    <p> {{ $chat->tamplate?->message }}
                                                        {{ $chat->amount ? 'Bid Amount: ' . currency_format($chat->amount) : '' }}
                                                    </p>
                                                    <span class='time'>{{ $chat->created_at->diffForHumans() }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                {{-- to stop admin to interfear in chat but he can view all the chat history --}}
                                <div class="send-box">
                                    <div class="alert alert-danger d-none" role="alert" id="errors">
                                        {{-- <ul class="li mb-0">1</ul> --}}
                                    </div>
                                    <form id="messageForm">
                                        {{-- <input type="text" id="msg" class="form-control"
                                                aria-label="message…" name="msg" placeholder="Write message…"
                                                required> --}}

                                        <select class="form-control mr-2" name="chat_tamplate_id" id="message">
                                            <option value="" selected disabled>Select Message</option>
                                            @foreach ($formats as $format)
                                                <option value="{{ $format->id }}"
                                                    data-with_amount="{{ $format->with_amount }}">
                                                    {{ $format->message }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="text" id="amount_input" class="form-control"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/(\.\d{2}).+/g, '$1');"
                                            aria-label="message…" name="amount" placeholder="Enter Bid Amount"
                                            style="display: none" disabled required>
                                        <input type="hidden" name="bid_id" id="bid_id"
                                            value="{{ $currentbid->id }}">
                                        <button type="button" onclick="validateAndSendMsg()">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                            Send</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- chatbox -->
            </div>
        </div>
        </div>
        </div>
    </section>
    <!-- char-area -->
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
    @include('modals.accept_bid_modal')
    {{-- @include('modals.reject_bid_modal') --}}
@endsection


@section('script')
    <script>
        let currentbid = {{ $currentbid->id }}

        $("#message").on("change", function() {
            var selectedOption = $(this).find('option:selected');
            var isAmountRequired = selectedOption.data('with_amount');
            if (isAmountRequired == 1) {
                $("#amount_input").removeAttr("disabled");
                $("#amount_input").show()
            } else {
                $("#amount_input").attr("disabled", "disabled");
                $("#amount_input").hide()
            }
        })

        function scrollToBottom() {
            var chatMessages = $('#scrollbody');
            var scrollHeight = chatMessages[0].scrollHeight;
            chatMessages.scrollTop(scrollHeight);
        }

        function appendMsg(msg, time = 'just now', from = 1) {
            // from: 1=send 2=receive
            let container = $("#msg_container");
            let newmsg = `<li class=${from==1?'repaly':'sender'}>  <p>${msg}</p> <span class="time">${time}</span></li>`
            container.append(newmsg)
            scrollToBottom();
        }

        function validateAndSendMsg() {
            var form = $('#messageForm');
            if (form[0].checkValidity()) {
                sendMsg(form);
            } else {
                form[0].reportValidity();
            }
        }

        function sendMsg() {
            var form = $('#messageForm');
            let formData = form.serialize()

            $.ajax({
                url: '{{ route('seller.chat.store') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // appendMsg(`${response.msg}: ${response.amount}`)
                    appendMsg(
                        `${response.tamplate.message} ${response.amount?"Bid Amount $ "+response.amount: ""}`)
                    $("#msg").val("")
                    $("#amount_input").val("")
                },
                error: function(xhr, status, error) {
                    alert("Error")
                    // alert(JSON.stringify(xhr.responseJSON.errors, null, 2))
                    showValidationError("#errors", xhr.responseJSON.errors)
                    // You can add more code here to handle error messages
                }
            });
        }

        setInterval(() => {
            updateChats()
        }, 10000);

        function updateChats() {
            $.ajax({
                url: '{{ route('seller.chat.updates', $product->slug) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    response.forEach((value, index, array) => {
                        if (value.chats_count) {
                            $("#msg_badge" + value.id).show()
                            $("#msg_badge" + value.id).text(value.chats_count)
                            $("#bid_amount" + value.id).text(value.amount)
                        }
                    })
                }
            });
        }

        function loadCaht(bid_id) {
            $(".chatperson").removeClass("bg-soft-secondary")
            $("#chatperson" + bid_id).addClass("bg-soft-secondary")
            $("#msg_badge" + bid_id).remove()

            event.preventDefault();
            let url = '{{ route('chat.history', ':bid_id') }}';
            let newUrl = url.replace(":bid_id", bid_id)
            $.ajax({
                url: newUrl,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $("#receiver").val(response.user_id)
                    $("#bid_id").val(bid_id)

                    currentbid = bid_id;
                    $("#msg_container").html(response.history)
                    $("#chat_header").html(response.chat_header)
                    scrollToBottom();
                },
                error: function(responce) {
                    // console.error('Error:', error);
                    // You can add more code here to handle error messages
                }
            });
        }


        function acceptOffer() {
            var url = '{{ route('accept.bid', ':bidId') }}'
            let newUrl = url.replace(":bidId", currentbid)
            $("#accept_bid_modal").modal("show");
            $("#accept-link").attr("href", newUrl);
        }


        // function rejectOffer() {
        //     var url = '{{ route('reject.bid', ':bidId') }}'
        //     let newUrl = url.replace(":bidId", currentbid)
        //     $("#reject_bid_modal").modal("show");
        //     $("#reject-link").attr("href", newUrl);
        // }

        $(document).ready(function() {
            scrollToBottom();
        });

        // Refresh page every 3Mins
        setInterval(() => {
            location.reload();
        }, 180000);

        function showValidationError(target, errors) {
            $(target).addClass("d-none");
            let error_content = `<ul>`;
            for (let key in errors) {
                let error = errors[key];
                if (error && error[0]) error_content += `<li>${error[0]}</li>`;
            }
            error_content += `</ul>`;
            $(target).removeClass("d-none").html(error_content);
        }
    </script>
@endsection
