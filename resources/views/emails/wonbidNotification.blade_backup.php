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
                                    Subject: Congratulations! You've Won the Auction for {{ $array['auction_no'] ?? 'N/A' }}
                                </h4>
                                <p style="padding:0px 20px;margin-bottom:35px;text-align:left;color:#888888;">
                                    Dear {{ $array['user_name'] ?? 'User' }} ,
                                    <br>
                                    Congratulations on winning the auction for {{ $array['auction_no'] ?? 'N/A' }} on NextGen
                                    Auctions & Marketplace!
                                    <br><br>
                                    We are pleased to inform you that you have successfully secured the item. To
                                    finalize your purchase, please proceed with the payment. All payments must be
                                    completed within 24 hours to avoid losing the item.
                                    <br>
                                    <br>
                                    To make your payment, please log in to your account.
                                    <br>
                                    <br>
                                    If you have a prior agreement with NextGen Auctions & Marketplace for the payment of
                                    this item in writing, please disregard this message and continue with the terms of
                                    your pre-arranged agreement. This policy helps us maintain the integrity and
                                    efficiency of our auction process, similar to the standards upheld in live auctions.
                                    <br>
                                    Please note that if payment is not received within the specified timeframe, NextGen
                                    Auctions & Marketplace reserves the right to pursue legal debt recovery for the
                                    item, including all associated costs, fees, and legal expenses.
                                    <br><br>
                                    We appreciate your prompt attention to this matter and look forward to completing
                                    this transaction with you.
                                    <br><br>
                                    Thank you for choosing NextGen Auctions & Marketplace.
                                    <br><br>
                                    Best regards,
                                    <br><br>
                                    The NextGen Auctions & Marketplace Team
                                    <br>
                                    <a style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;"
                                        href="https://www.nextgenauctions.com.au" target="_blank">
                                        www.nextgenauctions.com.au
                                    </a>
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
