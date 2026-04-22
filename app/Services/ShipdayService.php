<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Order;

class ShipdayService
{
    public function createOrder(Order $order)
    {
        if (!config('shipday.enabled')) {
            return null;
        }

        // Prevent duplicate Shipday orders
        if (!empty($order->shipday_order_id)) {
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . config('shipday.api_key'),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ])->post(config('shipday.base_url') . '/orders', [
            'orderNumber' => (string) $order->id,

            'customerName' => $order->client->name ?? 'Guest',
            'customerAddress' => $order->address ?? '',
            'customerPhoneNumber' => $order->phone ?? '',

            'restaurantName' => $order->restorant->name,
            'restaurantAddress' => $order->restorant->address,
            'restaurantPhoneNumber' => $order->restorant->phone,

            'expectedDeliveryDate' => now()->toDateString(),
            'expectedPickupTime' => now()->addMinutes(10)->format('H:i:s'),
            'expectedDeliveryTime' => now()->addMinutes(45)->format('H:i:s'),

            'pickupLatitude' => $order->restorant->lat,
            'pickupLongitude' => $order->restorant->lng,
            'deliveryLatitude' => $order->lat,
            'deliveryLongitude' => $order->lng,

            'deliveryFee' => (float) $order->delivery_fee,
            'tax' => (float) $order->tax,
            'discountAmount' => (float) $order->discount,
            'totalOrderCost' => (float) $order->order_price,

            'paymentMethod' => $order->payment_method === 'cod'
                ? 'cash'
                : 'credit_card',
        ]);

        if ($response->failed()) {
            logger()->error('Shipday order failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);
            return null;
        }

        $data = $response->json();

        // Save Shipday order ID
        $order->update([
            'shipday_order_id' => $data['id'] ?? null,
        ]);

        return $data;
    }
}
