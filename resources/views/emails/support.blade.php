<table border="0" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;margin:0 auto;background-color:#333;line-height:21px;text-align:left;font-size:12px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:#1b2143">
    @php
        $logo = get_setting('header_logo') ? get_setting('header_logo') : asset('images/logo.png');
    @endphp
    <tbody>
        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0" style="max-width:570px;width:100%;margin:15px auto;background-color:#191919;color:#1b2143;">
                    <tbody>
                        <tr>
                            <td style="padding:30px 40px 0;text-align:center">
                                <img height="80px" src="{{ uploaded_asset($logo) }}" style="height:80px;" class="CToWUd" data-bit="iit">
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:13px;color:#1b2143;line-height:22px;background-color:#191919;padding:0 20px;">
                                <h4 style="margin:35px 0 0;font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;">Subject: The NextGen Auctions & Marketplace Support Team</h4>
                                <p style="margin-bottom:35px;color:#888888;">{{ $content }} <br>
                                    {{ $sender }}<br>
                                    @php echo $details; @endphp
                                    <a class="btn btn-primary btn-md" href="{{ $link }}">{{ translate('See ticket') }}</a>
                                </p>
                                <p style="margin-bottom:35px;color:#888888;">Best regards,<br><br>The NextGen Auctions & Marketplace Support Team</p>
                                <p><a style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;" href="https://www.nextgenauctions.com.au" target="_blank">www.nextgenauctions.com.au</a></p>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:30px 0 15px;border-top:1px solid #191919;color:#888888;text-align:center;">
                                <h2 style="font-size:14px;color:#BE800F;">{{ __('Thank you for choosing us') }}!</h2>
                                <p style="font-size:12px;line-height:18px;color:#888888;">
                                    {{ __('For any questions or comments regarding our services, please feel free to contact us') }}<br>
                                    {{ __('at our helpline') }} 
                                    @if (get_setting('helpline_number'))
                                        {{ get_setting('helpline_number') }}
                                    @endif 
                                    | 
                                    @if (get_setting('contact_email'))
                                        {{ get_setting('contact_email') }}
                                    @endif 
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table border="0" cellspacing="1" style="max-width:630px;width:100%;padding:10px;margin:20px auto;text-align:justify;line-height:16px;font-size:11px;text-align:left;">
                    <tbody>
                        <tr>
                            <td>
                                <h4 style="font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;text-align:center;">
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
