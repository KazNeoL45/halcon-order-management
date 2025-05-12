<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Products;

class OrderApi extends Controller
{
    public function show($clientId, $orderId)
    {
        $order = Order::with([
            'client',
            'orderItems.product',
            'payments'
        ])->find($orderId);

        echo "hellou?";

        echo $order;

        if (!$order || $order->client_id != $clientId) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        $response = [
            'client_name' => $order->client->name,
            'order_id' => $order->id,
            'status' => $order->status,
            'total' => $order->orderItems->sum('subtotal'),
            'payment_method' => optional($order->payments->first())->method,
            'products' => $order->orderItems->map(function($item) {
                return [
                    'nombre' => $item->product->name,
                    'precio' => $item->product->price,
                    'cantidad' => $item->quantity,
                    'subtotal' => $item->subtotal
                ];
            })
        ];

        return response()->json($response);
    }
}
