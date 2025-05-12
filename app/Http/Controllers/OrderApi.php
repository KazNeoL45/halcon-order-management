<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use stdClass;

class OrderApi extends Controller
{
    public function show(Request $request)
    {
        $clientId = $request->query('clientId');
        $orderId = $request->query('orderId');

        #error_log('Client ID: ' . $clientId);
        #error_log('Client ID: ' . $clientId);

        $order = Order::with(['items', 'client'])->find($orderId);

        #error_log('client name ' . $order->client->name);

        $response = new stdClass();
        $response->client_name = $order->client->name;
        $response->order_id = $order->id;
        $response->status = $order->status;
        $response->total = $order->total;
        $response->products = $order->items->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal
            ];
        });

        if (!$order || $order->client_id != $clientId) {
            return response()->json(['message' => 'Orden no encontrada'], 404);
        }

        return response()->json($response, 200);
    }
}
