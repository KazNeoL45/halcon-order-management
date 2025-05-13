<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Products;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
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
        $countries = Country::all();
        $states = State::all();
        return view('orders.create', compact('clients', 'products', 'nextInvoice', 'countries', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate order, invoice, address, and items
        $request->validate([
            'client_id'       => 'required|exists:clients,id',
            'invoice_number'  => 'required|unique:orders,invoice_number',
            'invoice_date'    => 'required|date',
            'total'           => 'required|numeric',
            'notes'           => 'nullable|string',
            'street'          => 'required|string',
            'external_number' => 'required|string',
            'colony'          => 'required|string',
            'city'            => 'required|string',
            'state_id'        => 'required|exists:states,id',
            'zip_code'        => 'required|string',
            'country_id'      => 'required|exists:countries,id',
            'product_id'      => 'required|array|min:1',
            'product_id.*'    => 'required|exists:products,id',
            'quantity'        => 'required|array|min:1',
            'quantity.*'      => 'required|integer|min:1',
        ]);
        // Create address record
        $address = Address::create($request->only([
            'street', 'external_number', 'colony', 'city', 'state_id', 'zip_code', 'country_id',
        ]));
        // Create order record
        $order = Order::create(array_merge(
            $request->only(['client_id', 'invoice_number', 'invoice_date', 'status', 'total', 'notes']),
            ['address_id' => $address->id]
        ));
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
        $clients = Client::all();
        $products = Products::all();
        $countries = Country::all();
        $states = State::all();
        return view('orders.edit', compact('order', 'clients', 'products', 'countries', 'states'));
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
