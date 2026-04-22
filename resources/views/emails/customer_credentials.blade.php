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
                                <img height="80px" src="{{ uploaded_asset($logo) }}" style="height:80px;" class="CToWUd" data-bit="iit">
                            </td>
                        </tr>

                        <tr>
                            <td style="font-size:13px;color:#1b2143;line-height:22px;background-color:#191919">
                                <h2 style="padding:0px 20px;margin-top:35px;text-align:left;font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;">
                                    Subject: Your Login Credentials for NextGen Auctions & Marketplace
                                </h2>

                                <p style="padding:0px 20px;margin-bottom:35px;text-align:justify;color:#888888;">
                                    Dear {{ $array['name'] }},
                                    <br><br>

                                    Welcome to NextGen Auctions & Marketplace!
                                    <br><br>
                                    Your account has been successfully created. Below are your login credentials:
                                    <br><br>

                                    <strong>Email:</strong> {{ $array['email'] }}<br>
                                    <strong>Password:</strong> {{ $array['password'] }}<br><br>

                                    You can log in to your account by clicking the button below:
                                    <br><br>

                                    <a href="{{ $array['login_url'] }}" target="_blank"
                                        style="background-color:#BE800F;color:#fff;padding:10px 20px;border-radius:5px;text-decoration:none;font-weight:bold;">
                                        Login Now
                                    </a>

                                    <br><br>
                                    For your security, please consider changing your password after your first login.
                                    <br><br>
                                    If you have any issues accessing your account, feel free to contact our support team.
                                    <br><br>
                                    Best regards,
                                    <br>
                                    The NextGen Auctions & Marketplace Team
                                    <br>
                                    <a href="https://www.nextgenauctions.com.au" target="_blank" style="color:#BE800F;font-weight:bold;">
                                        www.nextgenauctions.com.au
                                    </a>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:30px 0 15px 0px;border-top:1px solid #191919;color:#888888;">
                                <div style="text-align:center">
                                    <h2 style="font-size:14px;color:#BE800F">
                                        <span style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;">
                                            {{ __('Thank you for choosing us') }}!
                                        </span>
                                    </h2>
                                    <p style="color:#1b2143">
                                        <span style="font-size:12px;line-height:18px;color:#888888;">
                                            {{ __('For any questions or comments regarding our services, please feel free to contact us') }}
                                            <br>
                                            {{ __('at our helpline') }}
                                            @if (get_setting('helpline_number'))
                                                {{ get_setting('helpline_number') }}
                                            @endif |
                                            @if (get_setting('contact_email'))
                                                {{ get_setting('contact_email') }}
                                            @endif
                                        </span>
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
                                <h4 style="font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;text-align:center">
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
