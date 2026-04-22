<table border="0" cellpadding="0" cellspacing="0"
    style="max-width:600px;width:100%;margin-left:auto;margin-right:auto;background-color:#333;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:#1b2143">

    @php
        $logo = get_setting('header_logo') ? uploaded_asset(get_setting('header_logo')) : asset('images/logo.png');
    @endphp

    <tbody>
        <tr>
            <td>
                <table border="0" cellpadding="0" cellspacing="0"
                    style="max-width:570px;width:100%;margin-left:auto;margin-right:auto;margin-top:15px;background-color:#191919">
                    <tr>
                        <td style="padding:30px 40px 0 40px;text-align:center">
                            <img src="{{ $logo }}" height="80" style="height:80px;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px 40px;color:#fff;">
                            <h2 style="color:#BE800F;text-align:left;">Auction Summary Report</h2>
                            <p style="font-size:13px;color:#bbb;margin-bottom:20px;">
                                The following auctions have ended. This summary contains the highest bids, reserve statuses, and winner details.
                            </p>

                            <table cellpadding="6" cellspacing="0" width="100%" style="border-collapse:collapse;background-color:#2a2a2a;color:#ccc;font-size:12px;text-align:left;">
                                <thead>
                                    <tr style="background-color:#1e1e1e;color:#BE800F">
                                        <th>Product</th>
                                        <th>Highest Bid</th>
                                        <th>Reserve</th>
                                        <th>Reserve Met</th>
                                        <th>Status</th>
                                        <th>Winner</th>
                                        <th>Email</th>
                                        <th>Link</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reportData as $row)
                                        <tr style="border-top:1px solid #444;">
                                            <td>{{ $row['product_name'] }}</td>
                                            <td>${{ number_format($row['highest_bid'], 2) }}</td>
                                            <td>${{ number_format($row['reserve_price'], 2) }}</td>
                                            <td>{{ $row['reserve_met'] ? 'Yes' : 'No' }}</td>
                                            <td>{{ $row['status'] }}</td>
                                            <td>{{ $row['winner_name'] }}</td>
                                            <td>{{ $row['winner_email'] }}</td>
                                            <td><a href="{{ $row['product_url'] }}" style="color:#BE800F;">View</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <p style="margin-top:20px;color:#999;">
                                You can follow up manually with users if the reserve was not met or proceed to invoice those who have won.
                            </p>

                            <p>
                                <a href="https://www.nextgenauctions.com.au" target="_blank" style="color:#BE800F;font-weight:bold;">
                                    www.nextgenauctions.com.au
                                </a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px;text-align:center;color:#888;font-size:12px;">
                            {{ get_setting('frontend_copyright_text', null, App::getLocale()) }}
                            <br>
                            @if(get_setting('helpline_number')) Helpline: {{ get_setting('helpline_number') }} @endif |
                            @if(get_setting('contact_email')) {{ get_setting('contact_email') }} @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
