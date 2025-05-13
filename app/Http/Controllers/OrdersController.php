<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Products;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('client')->orderBy('created_at', 'desc');

        if ($request->filled('invoice_number_filter')) {
            $query->where('invoice_number', 'like', '%' . $request->input('invoice_number_filter') . '%');
        }
        if ($request->filled('client_id_filter')) {
            $query->where('client_id', $request->input('client_id_filter'));
        }
        if ($request->filled('date_filter')) {
            $query->whereDate('invoice_date', $request->input('date_filter'));
        }
        if ($request->filled('status_filter')) {
            $query->where('status', $request->input('status_filter'));
        }

        $orders = $query->paginate(10)->appends($request->except('page'));
        $clients = Client::orderBy('name')->get();
        $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];

        return view('orders.index', compact('orders', 'clients', 'statuses', 'request'));
    }

    public function create()
    {
        $clients = Client::all();
        $products = Products::all();
        $lastOrder = Order::orderBy('id', 'desc')->first(); // More reliable way to get last order
        $nextInvoiceNumber = $lastOrder ? ((int)preg_replace('/[^0-9]/', '', $lastOrder->invoice_number) + 1) : 1;
        // Format your invoice number as needed, e.g., INV-001
        $nextInvoice = 'INV-' . str_pad($nextInvoiceNumber, 3, '0', STR_PAD_LEFT);


        return view('orders.create', compact('clients', 'products', 'nextInvoice'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'invoice_number' => 'required|unique:orders,invoice_number',
            'invoice_date'   => 'required|date',
            'status'         => 'required|string|in:pending,paid,shipped,delivered,cancelled',
            'total'          => 'required|numeric',
            'delivery_address' => 'nullable|string',
            'notes'          => 'nullable|string',
            'product_id'     => 'required|array|min:1',
            'product_id.*'   => 'required|exists:products,id',
            'quantity'       => 'required|array|min:1',
            'quantity.*'     => 'required|integer|min:1',
        ]);

        $orderData = $request->only([
            'client_id', 'invoice_number', 'invoice_date', 'status', 'total', 'delivery_address', 'notes'
        ]);
        $orderData['invoice_date'] = Carbon::parse($request->invoice_date); // Ensure correct date format

        $order = Order::create($orderData);

        foreach ($request->input('product_id') as $idx => $productId) {
            $qty = $request->input('quantity')[$idx] ?? 1;
            $product = Products::find($productId);
            if ($product) {
                $order->items()->create([
                    'product_id' => $productId,
                    'quantity'   => $qty,
                    'subtotal'   => $product->price * $qty,
                ]);
            }
        }
        return redirect()->route('orders.index')->with('success', 'Order created successfully.');
    }

    public function show(Order $order)
    {
        $order->load('client', 'items.product');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $clients = Client::all();
        $products = Products::all(); // If needed for item editing
        $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];
        $order->load('items');
        return view('orders.edit', compact('order', 'clients', 'products', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'client_id'      => 'sometimes|required|exists:clients,id',
            'invoice_number' => 'sometimes|required|unique:orders,invoice_number,' . $order->id,
            'invoice_date'   => 'sometimes|required|date',
            'status'         => 'sometimes|required|string|in:pending,paid,shipped,delivered,cancelled',
            'total'          => 'sometimes|required|numeric',
            'delivery_address' => 'nullable|string',
            'notes'          => 'nullable|string',
            // Add validation for items if they are editable
        ]);

        $updateData = $request->only([
             'client_id', 'invoice_number', 'invoice_date', 'status', 'total', 'delivery_address', 'notes'
        ]);
        if($request->has('invoice_date')) {
            $updateData['invoice_date'] = Carbon::parse($request->invoice_date);
        }

        $order->update($updateData);
        // Logic for updating order items would go here if applicable

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage. (Soft Delete)
     */
    public function destroy(Order $order)
    {
        // The SoftDeletes trait handles changing delete() to a soft delete.
        // Related items (OrderItems) are not automatically soft-deleted.
        // If you want to soft-delete OrderItems too, they would need the SoftDeletes trait
        // and you might do it here or via model events.
        // For now, we only soft-delete the order itself.
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order marked as deleted successfully.');
    }

    /**
     * Display a listing of the soft-deleted resources.
     */
    public function deletedOrders(Request $request)
    {
        $query = Order::onlyTrashed()->with('client')->orderBy('deleted_at', 'desc');

        // Optional: Add filters for the deleted orders view
        if ($request->filled('invoice_number_filter')) {
            $query->where('invoice_number', 'like', '%' . $request->input('invoice_number_filter') . '%');
        }
        // Add other filters if needed

        $deletedOrders = $query->paginate(10)->appends($request->except('page'));
        // You might want to pass clients and statuses if you implement full filtering on this page
        // $clients = Client::orderBy('name')->get();
        // $statuses = ['pending', 'paid', 'shipped', 'delivered', 'cancelled'];

        return view('orders.deleted', compact('deletedOrders', 'request'));
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restore($id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        // Optional: Restore related OrderItems if they were also soft-deleted
        // if (method_exists($order, 'items')) {
        //    $order->items()->onlyTrashed()->restore();
        // }

        return redirect()->route('orders.deleted')->with('success', 'Order restored successfully.');
    }
}