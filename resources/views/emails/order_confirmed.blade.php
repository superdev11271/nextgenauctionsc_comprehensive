<table border="0" cellpadding="0" cellspacing="0"
    style="max-width:600px;width:100%;margin-left:auto;margin-right:auto;background-color:#333;line-height:21px;text-align:left;font-size:12px;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;color:#888888">

    @php
        if (get_setting('header_logo')) {
            $logo = get_setting('header_logo');
        } else {
            $logo = asset('images/logo.png');
        }

        $order = App\Models\Order::where('code', $array['order_number'])->first();
    @endphp
    <tbody>
        <tr>
            <td style="padding-left:20px; padding-right:20px; background-color:#191919;">
                <table cellpadding="0" cellspacing="0" style="color:#888888; margin-top:20px; margin-bottom:20px;">
                    <tbody>
                        <tr>
                            <td style="padding:30px 20px 0 20px;text-align:center">
                                <img height="80px" src="{{ uploaded_asset($logo) }}" style="height:80px;"
                                    class="CToWUd" data-bit="iit">
                            </td>
                        </tr>

                        <tr>
                            <td
                                style="font-size:13px;color:#888888;line-height:22px;background-color:#191919; padding:20px;">
                                <h4
                                    @php
                                        $pickup_products = $order->orderDetails()->where('shipping_type', 'like', 'pickup_point')->get();
                                    @endphp
                                    style="margin-top:35px;text-align:left;font-size:14px;line-height:20px;color:#BE800F;font-weight:bold;">
                                    Subject: Order Confirmation and {{$pickup_products->count() == 0?'Details.':'Pick-Up Details for Your Auction Item.'}}
                                    <p style="margin-bottom:35px;text-align:left;color:#888888;">
                                    Dear {{ $array['name'] ?? 'User' }} ,
                                    <br>
                                    Congratulations on your successful purchase from NextGen Auctions & Marketplace!
                                    <br><br>
                                    We are pleased to confirm that your payment for {{ $array['order_number'] ?? '' }}
                                    has been
                                    received and processed. Your order is now confirmed.
                                    <br>

                                        <table cellpadding="0" cellspacing="0" style="border:1px solid #888; width:100%; margin-top:20px;">
                                            <thead class="">
                                                <tr style="border-top:1px solid #888888;">
                                                    <th style="border-right:1px solid #888; color:#888888; font-size:14px; padding-left:10px; padding-top:10px; padding-bottom:10px;  padding-right:10px;"
                                                        width="30%">{{ translate('Product') }}</th>
                                                    <th style="border-right:1px solid #888; color:#888888; font-size:14px; padding-left:10px; padding-top:10px; padding-bottom:10px;  padding-right:10px;"
                                                        data-breakpoints="md">{{ translate('Delievery Type') }}</th>
                                                    <th
                                                        style="padding-left:10px; padding-top:10px; color:#888888; font-size:14px; padding-bottom:10px;  padding-right:10px;">
                                                        {{ translate('Details') }}</th>


                                                </tr>
                                            </thead>
                                            <tbody class="fs-14">
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr style="border-top:1px solid #888888;">
                                                        <td style="font-size: 10px; border-right:1px solid #888; border-top:1px solid #888; color:#888; padding-left:10px; padding-top:10px; padding-bottom:10px;  padding-right:10px; ">
                                                            {{-- @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0) --}}
                                                                {{ $orderDetail->product->getTranslation('name') }}
                                                        </td>
                                                        <td style="font-size: 10px; border-right:1px solid #888; color:#888; border-top:1px solid #888; padding-left:10px; padding-top:10px; padding-bottom:10px;  padding-right:10px; ">
                                                            @if ($orderDetail->shipping_type == 'home_delivery')
                                                                {{ translate('Home Delivery') }}
                                                            @elseif ($orderDetail->shipping_type == 'pickup_point')
                                                                {{ translate('Pickup Point') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td
                                                            style="font-size: 10px; padding-left:10px; padding-top:10px; border-top:1px solid #888; color:#888; padding-bottom:10px;  padding-right:10px; ">
                                                            @if (getDeliveryType($orderDetail->product?->id) == 'pickup_point')
                                                                Please be informed that your pick-up date is scheduled for
                                                                {{ $orderDetail->product?->pickup_days}} {{ $orderDetail->product?->pickup_time}}
                                                            <br>
                                                                at the following address:
                                                            <br>
                                                                {{ $orderDetail->product?->pickup_address }}
                                                            @elseif($orderDetail->product?->est_shipping_days)
                                                                Product Will be delivered within {{ $orderDetail-product?->est_shipping_days}} days from the date of the order confirmation.
                                                            @else
                                                            -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    <br>
                                    To ensure a smooth pick-up process, please bring your paid receipt from NextGen
                                    Auctions & Marketplace. Auction items cannot be picked up without the paid receipt,
                                    and all items must be collected strictly on the advertised collection days (No
                                    exceptions).
                                    <br>
                                    If you have already arranged an alternative pick-up date prior to the end of the
                                    auction, please adhere to your pre-arranged schedule.
                                    <br><br>

                                    Thank you for your prompt attention and cooperation. We appreciate your business and
                                    look forward to assisting you further.
                                    <br><br>
                                    Best regards,
                                    <br><br>
                                    The NextGen Auctions & Marketplace Team
                                    <br>
                                    <a style="font-size:14px;line-height:36px;color:#BE800F;font-weight:bold;"
                                        href="https://www.nextgenauctions.com.au" target="_blank">
                                        www.nextgenauctions.com.au
                                    </a>
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
