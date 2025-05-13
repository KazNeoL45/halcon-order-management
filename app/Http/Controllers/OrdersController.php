<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Products;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //$clients = Client::with('address')->get();
        $clients = Client::all();
        $products = Products::all();
        // determine next invoice number
        $last = Order::max('invoice_number');
        $nextInvoice = $last ? ((int)$last + 1) : 1;
        return view('orders.create', compact('clients', 'products', 'nextInvoice'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate order, invoice, and items
        $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'invoice_number'  => 'required|unique:orders,invoice_number',
            'invoice_date'    => 'required|date',
            'status'          => 'required|string|max:255',
            'total'           => 'required|numeric',
            'delivery_address'=> 'nullable|string',
            'notes'           => 'nullable|string',
            'product_id'      => 'required|array|min:1',
            'product_id.*'    => 'required|exists:products,id',
            'quantity'        => 'required|array|min:1',
            'quantity.*'      => 'required|integer|min:1',
        ]);
        // Create order record
        $order = Order::create($request->only([
            'client_id', 'invoice_number', 'invoice_date', 'status', 'total', 'delivery_address', 'notes'
        ]));
        // Attach each item to order
        foreach ($request->input('product_id') as $idx => $productId) {
            $qty = $request->input('quantity')[$idx] ?? 1;
            $product = Products::find($productId);
            $order->items()->create([
                'product_id' => $productId,
                'quantity'   => $qty,
                'subtotal'   => $product->price * $qty,
            ]);
        }
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $orders)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $orders->update($request->all());
        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
