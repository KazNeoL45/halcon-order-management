<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderApi extends Controller
{
    public function show(Request $request)
    {
        $clientId = $request->query('clientId');
        $orderId = $request->query('orderId');

        #error_log('Client ID: ' . $clientId);
        #error_log('Client ID: ' . $clientId);

        $order = Order::with(['items.product', 'client', 'address.state', 'address.country'])->find($orderId);

        if (!$order || $order->client_id != $clientId) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        $response = [
            'client_name' => $order->client->name,
            'order_id'    => $order->id,
            'status'      => $order->status,
            'total'       => $order->total,
            'address'     => [
                'street'          => $order->address->street,
                'external_number' => $order->address->external_number,
                'colony'          => $order->address->colony,
                'city'            => $order->address->city,
                'state'           => $order->address->state->name ?? null,
                'zip_code'        => $order->address->zip_code,
                'country'         => $order->address->country->name ?? null,
            ],
            'products'    => $order->items->map(function ($item) {
                return [
                    'id'       => $item->id,
                    'name'     => $item->product->name,
                    'price'    => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->subtotal,
                ];
            })->values(),
        ];

        return response()->json($response, 200);
    }
}
