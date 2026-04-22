<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ translate('INVOICE') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <style media="all">
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            font-size: 0.875rem;
            font-family: '<?php echo $font_family; ?>';
            font-weight: normal;
            direction: <?php echo $direction; ?>;
            text-align: <?php echo $text_align; ?>;
            padding: 0;
            margin: 0;
        }

        .gry-color *,
        .gry-color {
            color: #000;
        }

        table {
            width: 100%;
        }

        table th {
            font-weight: normal;
        }

        table.padding th {
            padding: .25rem .7rem;
        }

        table.padding td {
            padding: .25rem .7rem;
        }

        table.sm-padding td {
            padding: .1rem .7rem;
        }

        .border-bottom td,
        .border-bottom th {
            border-bottom: 1px solid #eceff4;
        }

        .text-left {
            text-align: <?php echo $text_align; ?>;
        }

        .text-right {
            text-align: <?php echo $not_text_align; ?>;
        }
    </style>
</head>

<body>
    <div>

        @php
            $logo = get_setting('header_logo');
        @endphp

        <div style="background: #eceff4;padding: 1rem;">
            <table>
                <tr>
                    <td>
                        @if ($logo != null)
                            <img src="{{ uploaded_asset($logo) }}" height="30" style="display:inline-block;">
                        @else
                            <img src="{{ static_asset('assets/img/logo.png') }}" height="30"
                                style="display:inline-block;">
                        @endif
                    </td>
                    <td style="font-size: 1.5rem;" class="text-right strong">{{ translate('INVOICE') }}</td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="font-size: 1rem;" class="strong">{{ get_setting('site_name') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ get_setting('contact_address') }}</td>
                    <td class="text-right"></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}: {{ get_setting('contact_email') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order ID') }}:</span> <span
                            class="strong">{{ $order->code }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}: {{ get_setting('contact_phone') }}</td>
                    <td class="text-right small"><span class="gry-color small">{{ translate('Order Date') }}:</span>
                        <span class=" strong">{{ date('d-m-Y', $order->date) }}</span></td>
                </tr>
                <tr>
                    <td class="gry-color small"></td>
                    <td class="text-right small">
                        <span class="gry-color small">
                            {{ translate('Payment method') }}:
                        </span>
                        <span class="strong">
                            {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                        </span>
                        <br>
                        <span class="gry-color small">
                            {{ translate("Buyers' Premium") }}:
                        </span>
                        @php
                            $selllersPremium = $order->seller->shop?->commission ?? (get_setting('vendor_commission') ?? 0);
                            $totalBuyersPremium = 0;
                        @endphp
                        <span class="strong">
                            {{  $selllersPremium }}%
                        </span>
                    </td>
                </tr>
            </table>

        </div>

        <div style="padding: 1rem;padding-bottom: 0">
            <table>
                @php
                    $shipping_address = json_decode($order->shipping_address);
                @endphp
                <tr>
                    <td class="strong small gry-color">{{ translate('Bill to') }}:</td>
                </tr>
                <tr>
                    <td class="strong">{{ isset($shipping_address->name) ? $shipping_address->name : '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">
                        {{ isset($shipping_address->address) ? $shipping_address->address : '' }},
                        {{ isset($shipping_address->city) ? $shipping_address->city : '' }}, @if (isset(json_decode($order->shipping_address)->state))
                            {{ json_decode($order->shipping_address)->state }} -
                        @endif
                        {{ isset($shipping_address->postal_code) ? $shipping_address->postal_code : '' }},
                        {{ isset($shipping_address->country) ? $shipping_address->country : '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Email') }}:
                        {{ isset($shipping_address->email) ? $shipping_address->email : '' }}</td>
                </tr>
                <tr>
                    <td class="gry-color small">{{ translate('Phone') }}:
                        {{ isset($shipping_address->phone) ? $shipping_address->phone : '' }}</td>
                </tr>
            </table>
        </div>

        <div style="padding: 1rem;">
            <table class="padding text-left small border-bottom">
                <thead>
                    <tr class="gry-color" style="background: #eceff4;">
                        <th width="35%" class="text-left">{{ translate('Product Name') }}</th>
                        <th width="35%" class="text-left">{{ translate('Description') }}</th>
                        <th width="15%" class="text-left">{{ translate('Delivery Type') }}</th>
                        <th width="10%" class="text-left">{{ translate('Qty') }}</th>
                        <th width="10%" class="text-left">{{ translate('Tax') }}</th>
                        <th width="15%" class="text-left">{{ translate('Price') }}</th>
                        {{-- <th width="15%" class="text-left">{{ translate("Buyers' Premium") }}</th> --}}
                        <th width="15%" class="text-right">{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody class="strong">
                    @foreach ($order->orderDetails as $key => $orderDetail)
                        @if ($orderDetail->product != null)
                        @php
                        $buyersPremium = 0;
                        @endphp
                            <tr class="">
                                <td>
                                    {{ $orderDetail->product->name }}
                                    @if ($orderDetail->variation != null)
                                        ({{ $orderDetail->variation }})
                                    @endif
                                    <br>
                                    @php
                                        $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                    @endphp
                                    @if (isset($product_stock['sku']) && $product_stock['sku'] != '')
                                        <small>
                                            {{ translate('SKU') }}: {{ $product_stock['sku'] }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        Product Type: {{$orderDetail->product->auction_product?"Auction Product":"Marketplace Product"}}
                                        <br>
                                        @if($orderDetail->product->auction_product == 1)
                                        Auction: {{ ucfirst($orderDetail->product->getCollectionLabel()) }}
                                        <br>
                                        Lot: {{ ucfirst($orderDetail->product->getTranslation('lot')) }}
                                        <br>
                                        
                                        @if($orderDetail->product->pickup_days)
                                        Pickup Date: {{$orderDetail->product->pickup_days}}
                                        <br>
                                        @endif
                                        @if($orderDetail->product->pickup_time)
                                        Pickup Time: {{$orderDetail->product->pickup_time}}
                                        <br>
                                        @endif
                                        
                                        @if($orderDetail->product->pickup_address)
                                        Pickup Address: {{$orderDetail->product->pickup_address}}
                                        <br>
                                        @endif
                                        
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <ul class="mb-0">
                                                    @foreach ($orderDetail->product->attrs as $attribute)
                                                    @if (!in_array($attribute->type(), [0, 2]))
                                                    <li><span>{{ $attribute->attribute_name }}:</span>
                                                        {{ $attribute->value }}
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="row">
                                            <div class="col-md-12">
                                                <ul class="mb-0">
                                                    @foreach ($orderDetail->product->attrs as $attribute)
                                                    @if (in_array($attribute->type(), [2]))
                                                    <li><span>{{ $attribute->attribute_name }} : </span>
                                                        {{ $attribute->value }}
                                                    </li>
                                                    @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div> --}}
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($orderDetail->shipping_type == 'home_delivery')
                                        {{ translate('Home Delivery') }}
                                    @elseif ($orderDetail->shipping_type == 'pickup_point')
                                        {{ translate('Pickup Point') }}
                                    @endif
                                </td>
                                <td class="">{{ $orderDetail->quantity }}</td>
                                <td class="currency">{{ single_price($orderDetail->tax / $orderDetail->quantity) }}</td>
                                @if ($orderDetail->product->auction_product == 1)
                                    @php
                                        $buyersPremium = ($orderDetail->price*$selllersPremium/100)*$orderDetail->quantity;
                                        $totalBuyersPremium += $buyersPremium
                                    @endphp
                                @endif
                                <td class="currency">{{ single_price(($orderDetail->price / $orderDetail->quantity)-$buyersPremium) }}
                                {{-- <td class="currency">
                                    {{ single_price($buyersPremium) }}
                                    @else
                                    N/A
                                </td> --}}
                                <td class="text-right currency">
                                    {{ single_price($orderDetail->price + $orderDetail->tax-$buyersPremium) }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="padding:0 1.5rem;">
            <table class="text-right sm-padding small strong">
                <thead>
                    <tr>
                        <th width="60%"></th>
                        <th width="40%"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">
                            @php
                            $removedXML = '<?xml version="1.0" encoding="UTF-8"?>'
                            @endphp
                            {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
                        </td>
                        <td>
                            <table class="text-right sm-padding small strong">
                                <tbody>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Sub Total') }}</th>
                                        <td class="currency">{{ single_price($order->orderDetails->sum('price')-$totalBuyersPremium) }}
                                        </td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">{{ translate('Total Tax') }}</th>
                                        <td class="currency">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                                    </tr>
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Shipping Cost') }}</th>
                                        <td class="currency">
                                            {{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                                    </tr>
                                    <tr class="border-bottom">
                                        <th class="gry-color text-left">{{ translate('Coupon Discount') }}</th>
                                        <td class="currency">{{ single_price($order->coupon_discount) }}</td>
                                    </tr>
                                    @if($totalBuyersPremium)
                                    <tr>
                                        <th class="gry-color text-left">{{ translate('Buyers Premium') }}</th>
                                        <td class="currency">{{ single_price($totalBuyersPremium) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th class="text-left strong">{{ translate('Grand Total') }}</th>
                                        <td class="currency">{{ single_price($order->grand_total) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @php
            // $pickup_products = $order->whereHas('orderDetails',fn ($query) => $query->where('shipping_type', 'like', 'pickup_point'))->get();
            $pickup_products = $order->orderDetails()->where('shipping_type', 'like', 'pickup_point')->get();
        @endphp
        {{-- Pickup Detail Start --}}
        @if ($pickup_products->count())
            <div style="padding: 1rem; width: 70%;">
                <h2>Pickup Details</h2>
                <table class="padding text-left small border-bottom">
                    <thead>
                        <tr class="gry-color" style="background: #eceff4;">
                            <th class="text-left">{{ translate('Product Name') }}</th>
                            <th class="text-left">{{ translate('Date / Time') }}</th>
                            <th class="text-left">{{ translate('Address') }}</th>
                        </tr>
                    </thead>
                    <tbody class="strong">
                        @foreach ($pickup_products as $key => $orderDetail)
                            @if ($orderDetail->product != null)
                                <tr class="">
                                    <td>{{ $orderDetail->product->name }}</td>
                                    <td> {{ $orderDetail->product?->pickup_days }}
                                        {{ $orderDetail->product?->pickup_time }} </td>
                                    <td> {{ $orderDetail->product?->pickup_address }} </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        {{-- Pickup Detail End --}}
    </div>
</body>

</html>
