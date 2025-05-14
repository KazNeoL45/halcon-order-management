<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Client;
use App\Models\Products;
use App\Models\Address;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use Carbon\Carbon; // Import Carbon

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('client')->orderBy('id', 'desc');

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
        $statuses = ['in_process', 'in_route', 'ordered', 'delivered', 'cancelled'];

        return view('orders.index', compact('orders', 'clients', 'statuses', 'request'));
    }

    public function create()
    {
        $clients = Client::all();
        $products = Products::all();
        // determine next invoice number
        $last = Order::max('invoice_number');
        $nextInvoice = $last ? ((int)$last + 1) : 1;
        $countries = Country::all();
        $states = State::all();
        return view('orders.create', compact('clients', 'products', 'nextInvoice', 'countries', 'states'));
    }

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
        $products = Products::all();
        $countries = Country::all();
        $states = State::all();
        return view('orders.edit', compact('order', 'clients', 'products', 'countries', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        // Validate order, address, and items (invoice_number unique except current)
        $request->validate([
            'status'          => 'required|in:pending,paid,shipped,delivered,cancelled',
            'client_id'       => 'required|exists:clients,id',
            'invoice_number'  => 'required|unique:orders,invoice_number,' . $order->id,
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

        // Update or create address
        $addrData = $request->only([
            'street', 'external_number', 'colony', 'city', 'state_id', 'zip_code', 'country_id',
        ]);
        if ($order->address) {
            $order->address->update($addrData);
        } else {
            $address = Address::create($addrData);
            $order->address_id = $address->id;
        }

        // Update order fields
        $order->update($request->only([
            'status', 'client_id', 'invoice_number', 'invoice_date', 'total', 'notes'
        ]));

        // Sync items: remove existing and re-add
        $order->items()->delete();
        foreach ($request->input('product_id') as $idx => $productId) {
            $qty = $request->input('quantity')[$idx] ?? 1;
            $product = Products::find($productId);
            $order->items()->create([
                'product_id' => $productId,
                'quantity'   => $qty,
                'subtotal'   => $product->price * $qty,
            ]);
        }

        // Save in case address_id was set
        $order->save();

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

    /**
     * Mark the specified order as in transit and upload load photo.
     */
    public function markInTransit(Request $request, Order $order)
    {
        $request->validate([
            'load_photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('load_photo')) {
            $path = $request->file('load_photo')->store('load_photos', 'public');
            $order->load_photo = $path;
        }

        $order->status = 'in_route';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order marked as in transit successfully.');
    }
    /**
     * Mark the specified order as in process.
     */
    public function markInProcess(Request $request, Order $order)
    {
        $order->status = 'in_process';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order marked as in process successfully.');
    }
    /**
     * Mark the specified order as delivered and upload unload photo.
     */
    public function markDelivered(Request $request, Order $order)
    {
        $request->validate([
            'unload_photo' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('unload_photo')) {
            $path = $request->file('unload_photo')->store('unload_photos', 'public');
            $order->unload_photo = $path;
        }

        $order->status = 'delivered';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Order marked as delivered successfully.');
    }
}
