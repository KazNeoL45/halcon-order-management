@extends('layouts.app')

@section('content')

<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Halcon Managment - Orders</h2>
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
            <i class="fa fa-plus"></i> Add New order</a>
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
                    <th width="230px">Actions</th>
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
                                <i class="fa-solid fa-pen-to-square"></i> Edit</a>
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
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection