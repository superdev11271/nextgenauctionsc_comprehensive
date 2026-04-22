<table border="0" cellpadding="0" cellspacing="0"
    style="max-width:600px;width:100%;margin-left:auto;margin-right:auto;background-color:#333;line-height:21px;text-align:left;font-size:12px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:#1b2143">

    @php
        if (get_setting('header_logo')) {
            $logo = get_setting('header_logo');
        } else {
            $logo = asset('images/logo.png');
        }
    @endphp
    <tbody>
        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0"
                    style="color:#1b2143;max-width:570px;width:100%;margin-left:auto;margin-right:auto;margin-top:15px;background-color:#191919">
                    <tbody>
                        <tr>
                            <td style="padding:30px 40px 0 40px;text-align:center">
                                <img height="80px" src="{{ uploaded_asset($logo) }}" style="height:80px;" class="CToWUd"
                                    data-bit="iit">
                            </td>
                        </tr>

                        <tr>
                            <td style="font-size:13px;color:#1b2143;line-height:22px;background-color:#191919">
                                <h4
                                    style="padding:0px 20px;margin-top:35px;text-align:left;font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;">
                                    @if ($array['auction_status'] === 'Upcoming')
                                        Subject: Hurry up and be prepared for bidding! Your auction will start within {{ $array['hours'] ?? '' }} {{get_setting('auction_time_type') }} .
                                    @else
                                        Subject: Hurry up and finish your bidding! Your auction will end within {{ $array['hours'] ?? '' }} {{get_setting('auction_time_type') }}.
                                    @endif

                                </h4>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center" align="center">
                                <img src="{{ $array['image']?? ''}}" style="width:100%; height:100%;" with="100%" />
                            <td>
                        </tr>
                        <tr>
                            <td style="text-align: center" align="center">
                                <a href="{{ $array['link'] ?? '' }}" style="
                                    color: #BE800F;
                                    text-align: center;
                                    border: 1px solid #BE800F;
                                    border-radius: 4px;
                                    padding: 10px 8px;
                                    z-index: 1;
                                    text-decoration: none;
                                    background: transparent;
                                    position: relative;
                                    align-items: center;
                                    display: inline-flex;
                                    justify-content: center;
                                    gap: 4px;
                                    font-size: 14px;
                                    line-height: 17px !important;
                                    font-weight: 600;
                                    white-space: nowrap;
                                    margin-top:20px;
                                ">View Product</a>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:30px 0 15px 0px;border-top:1px solid #191919;color:#888888;">
                                <div style="text-align:center">
                                    <h2 style="font-size:14px;color:#BE800F"><span
                                            style="font-size:14px">{{ __('Thank you for choosing us') }}!</span></h2>
                                    <p style="color:#1b2143"><span
                                            style="font-size:12px;line-height:18px;color:#888888;">
                                            {{ __('For any questions or comments regarding our services, please feel free to contact us') }}
                                            <br>
                                            {{ __('at our helpline') }} @if (get_setting('helpline_number'))
                                                {{ get_setting('helpline_number') }}
                                                @endif | @if (get_setting('contact_email'))
                                                    {{ get_setting('contact_email') }}
                                                @endif </span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" cellspacing="1"
                    style="max-width:630px;width:100%;padding:10px;margin-left:auto;text-align:justify;margin-right:auto;margin-top:20px;margin-bottom:20px;text-align:left;line-height:16px;font-size:11px">
                    <tbody>
                        <tr>
                            <td>
                                <h4
                                    style="font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;text-align:center">
                                    {!! get_setting('frontend_copyright_text', null, App::getLocale()) !!}
                                </h4>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
