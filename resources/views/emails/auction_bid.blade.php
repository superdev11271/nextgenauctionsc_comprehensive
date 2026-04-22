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
                                    Subject: Someone Placed a Higher Bid Than Yours : {{ $array['auction_no'] ?? 'N/A' }}
                                </h4>
                                <p style="padding:0px 20px;margin-bottom:35px;text-align:left;color:#888888;">
                                    Dear Bidders,
                                    <br>
                                    Thank you for participating in the recent auction on NextGen Auctions & Marketplace.
                                    <br><br>
                                    We wanted to inform you that a new bid has been placed on {{ $array['auction_no'] ?? 'N/A' }},
                                    surpassing your highest bid. We understand that this item is of great interest to
                                    you, and we wanted to give you the opportunity to increase your bid if you wish to
                                    remain competitive.
                                    <br>
                                    <br>
                                    <span
                                        style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;">Highest
                                        Bid {{ currency_symbol() }}{{ $array['higest_bid'] ?? '' }} </span>
                                    <br>
                                    @if ($link)
                                        <a style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;"
                                            href="{{ $link }}" target="_blank">{{ translate('Change Bid') }}</a>
                                    @endif
                                    <br><br>
                                    We appreciate your continued participation and look forward to helping you secure
                                    this item at a price that is mutually beneficial.
                                    <br>
                                    Thank you for choosing NextGen Auctions & Marketplace.
                                    <br>
                                    Best regards,
                                    <br><br>
                                    The NextGen Auctions & Marketplace Team
                                    <br>
                                    <a style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;"
                                        href="https://www.nextgenauctions.com.au" target="_blank">
                                        www.nextgenauctions.com.au
                                    </a>
                                    <br>
                                    Please do not reply to this email.
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
    </tbody>
</table>
