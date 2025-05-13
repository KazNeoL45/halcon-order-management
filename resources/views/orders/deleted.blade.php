@extends('layouts.app')

@section('content')
<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Halcon Managment - Deleted Orders</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        {{-- Optional: Add filter form here if needed, similar to index.blade.php --}}
        {{--
        <div class="mb-3">
            <form method="GET" action="{{ route('orders.deleted') }}">
                <div class="row g-2">
                    <div class="col-md">
                        <input type="text" name="invoice_number_filter" class="form-control form-control-sm" placeholder="Invoice Number" value="{{ $request->input('invoice_number_filter') }}">
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-sm">Filter Deleted</button>
                        <a href="{{ route('orders.deleted') }}" class="btn btn-secondary btn-sm">Clear</a>
                    </div>
                </div>
            </form>
        </div>
        --}}

        <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-3">
            <a class="btn btn-secondary btn-sm" href="{{ route('orders.index') }}">
                <i class="fa fa-arrow-left"></i> Back to Orders List
            </a>
        </div>

        <table class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <th width="80px">Order ID</th>
                    <th>Invoice No.</th>
                    <th>Client</th>
                    <th>Invoice Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Deleted At</th>
                    <th width="150px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deletedOrders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->invoice_number }}</td>
                        <td>{{ $order->client->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->invoice_date)->format('Y-m-d') }}</td>
                        <td>
                            @php
                                $statusClass = match($order->status) {
                                    'pending'   => 'badge bg-warning text-dark',
                                    'paid'      => 'badge bg-primary',
                                    'shipped'   => 'badge bg-info text-dark',
                                    'delivered' => 'badge bg-success',
                                    'cancelled' => 'badge bg-danger',
                                    default     => 'badge bg-secondary',
                                };
                            @endphp
                            <span class="{{ $statusClass }} d-inline-flex justify-content-center align-items-center p-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>{{ number_format($order->total, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->deleted_at)->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <form action="{{ route('orders.restore', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fa-solid fa-undo"></i> Restore
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No deleted orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            {{ $deletedOrders->links() }}
        </div>
    </div>
</div>
@endsection