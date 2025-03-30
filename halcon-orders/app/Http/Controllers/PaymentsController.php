<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payments::all();
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        Payments::create($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payments $payments)
    {
        return view('payments.show', compact('payments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payments $payments)
    {
        return view('payments.edit', compact('payments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payments $payments)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $payments->update($request->all());
        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payments $payments)
    {
        $payments->delete();
        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
