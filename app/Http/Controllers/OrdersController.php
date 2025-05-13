<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request; // Asegúrate de que Request está importado

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Inyecta Request para acceder a los parámetros de la URL
    {
        // Inicia la consulta base
        $query = Order::query();

        if ($request->has('invoice_number') && $request->input('invoice_number') != '') {
            $query->where('id', $request->input('invoice_number'));
        }

        if ($request->filled('client_id')) {
    $query->whereHas('client', function ($q) use ($request) {
        $q->where('name', 'ILIKE', '%' . $request->client_id . '%');
    });
}
       
        if ($request->has('client_name') && $request->input('client_name') != '') {
            $query->whereHas('client', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('client_name') . '%');
            });
        }

        if ($request->has('order_date') && $request->input('order_date') != '') {
            $query->whereDate('created_at', $request->input('order_date'));
        }

        if ($request->has('status') && $request->input('status') != '') {
            $query->where('status', $request->input('status'));
        }

        $orders = $query->with('client')->latest()->paginate(10); 
        $filterInputs = $request->only(['invoice_number', 'client_id', 'client_name', 'order_date', 'status']);

        return view('orders.index', compact('orders', 'filterInputs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id', 
            'status' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'items' => 'nullable|string',
        ]);

        Order::create($validated);

        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Carga la relación client si no lo hiciste antes
        $order->load('client');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // Podrías necesitar pasar una lista de clientes aquí también
        // $clients = \App\Models\Client::all();
        // return view('orders.edit', compact('order', 'clients'));
        $order->load('client');
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order) // Cambiado $orders a $order para consistencia
    {
        $request->validate([
            // Estas reglas de validación parecen ser para 'order_items' o algo similar, no para la orden en sí.
            // Ajusta estas validaciones según los campos que realmente estés actualizando en la orden.
            // Por ejemplo, si actualizas el status o el total:
            'client_id' => 'sometimes|required|exists:clients,id',
            'status' => 'sometimes|required|string|max:255',
            'total' => 'sometimes|required|numeric|min:0',
            'items' => 'nullable|string',
            // Si estas son las validaciones correctas, asegúrate que tu formulario 'edit' tenga estos campos.
            // 'product_id' => 'required|exists:products,id',
            // 'user_id' => 'required|exists:users,id', // ¿Estás seguro que una orden tiene user_id y product_id directamente?
            // 'quantity' => 'required|integer|min:1',
        ]);

        $order->update($request->all()); // Asegúrate de que los campos en $request->all() sean fillable en tu modelo Order.
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