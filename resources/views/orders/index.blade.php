@extends('layouts.app')

@section('content')

<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Orders</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="mb-3">
            <form method="GET" action="{{ route('orders.index') }}">
                <div class="row g-2">
                    <div class="col-md">
                        <input type="text" name="invoice_number_filter" class="form-control form-control-sm" placeholder="Invoice Number" value="{{ $request->input('invoice_number_filter') }}">
                    </div>
                    <div class="col-md">
                        <select name="client_id_filter" class="form-select form-select-sm">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ $request->input('client_id_filter') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md">
                        <input type="date" name="date_filter" class="form-control form-control-sm" value="{{ $request->input('date_filter') }}">
                    </div>
                    <div class="col-md">
                        <select name="status_filter" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $request->input('status_filter') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
                <a class="btn btn-warning btn-sm" href="{{ route('orders.deleted') }}">
        <i class="fa fa-trash-restore"></i> View Deleted Orders
    </a>

            <a class="btn btn-success btn-sm" href="{{ route('orders.create') }}">
            <i class="fa fa-plus"></i> Add New Order</a>
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
                    <th width="200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
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
                        <td>
                            <form action="{{ route('orders.destroy',$order->id) }}" method="POST" class="d-inline-flex">
                                <a class="btn btn-info btn-sm me-1"
                                href="{{ route('orders.show',$order->id) }}">
                                <i class="fa-solid fa-list"></i> Show</a>
                                <a class="btn btn-primary btn-sm me-1"
                                   href="{{ route('orders.edit',$order->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <button type="button" class="btn btn-warning btn-sm me-1" x-data="" x-on:click.prevent="$dispatch('open-modal', 'mark-order-{{ $order->id }}-in-transit')">
                                    <i class="fa-solid fa-truck"></i> Mark as In Transit
                                </button>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">
                                <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">There are no data matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Modals for marking orders as in transit --}}
        @foreach ($orders as $order)
            <x-modal name="mark-order-{{ $order->id }}-in-transit" focusable>
                <form action="{{ route('orders.markInTransit', $order) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <h2 class="text-lg font-medium text-gray-900">Mark Order #{{ $order->id }} as In Transit</h2>
                    <div class="mt-4 mb-4">
                        <label for="load_photo_{{ $order->id }}" class="form-label">Load Photo</label>
                        <input type="file" class="form-control" id="load_photo_{{ $order->id }}" name="load_photo" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="btn btn-secondary btn-sm me-2" x-on:click="$dispatch('close')">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Mark as In Transit</button>
                    </div>
                </form>
            </x-modal>
        @endforeach
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection