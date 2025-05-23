@extends('layouts.app')

@section('content')

<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Orders </h2>
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
                    <option value="ordered" {{ $request->input('status_filter') == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="in process" {{ $request->input('status_filter') == 'in process' ? 'selected' : '' }}>In Process</option>
                    <option value="in route" {{ $request->input('status_filter') == 'in route' ? 'selected' : '' }}>In Route</option>
                    <option value="delivered" {{ $request->input('status_filter') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $request->input('status_filter') == 'cancelled' ? 'selected' : '' }}>cancelled</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Clear</a>
            </div>
        </div>
    </form>
</div>

        @php $role = auth()->user()->role->name; @endphp
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            @if(in_array($role, ['Admin', 'Sales']))
                <a class="btn btn-warning btn-sm" href="{{ route('orders.deleted') }}">
                    <i class="fa fa-trash-restore"></i> View Deleted Orders
                </a>
                <a class="btn btn-success btn-sm" href="{{ route('orders.create') }}">
                    <i class="fa fa-plus"></i> Add New Order
                </a>
            @endif
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
                                $statusName = match($order->status) {
                                    'ordered'   => 'Ordered',
                                    'in_process' => 'In Process',
                                    'in_route'   => 'In Route',
                                    'delivered' => 'Delivered',
                                    'cancelled' => 'Cancelled',
                                    default     => 'Unknown',
                                };
                                $statusClass = match($order->status) {
                                    'ordered'   => 'badge bg-warning text-dark',
                                    'in_process'      => 'badge bg-primary',
                                    'in_route'   => 'badge bg-info text-dark',
                                    'delivered' => 'badge bg-success',
                                    'cancelled' => 'badge bg-danger',
                                    default     => 'badge bg-secondary',
                                };
                            @endphp
                            <span class="{{ $statusClass }} d-inline-flex justify-content-center align-items-center p-2">
                                {{ $statusName }}
                            </span>
                        </td>
                        <td>{{ number_format($order->total, 2) }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="actionsDropdown{{ $order->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionsDropdown{{ $order->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.show',$order->id) }}">
                                            <i class="fa-solid fa-list"></i> Show
                                        </a>
                                    </li>
                                    @if(in_array($role, ['Admin', 'Sales']))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('orders.edit',$order->id) }}">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                    </li>
                                    @endif
                                    @if($order->status == 'ordered' && in_array($role, ['Admin', 'Warehouse']))
                                        <li>
                                            <button type="button" class="dropdown-item" x-data="" x-on:click.prevent="$dispatch('open-modal', 'mark-order-{{ $order->id }}-in-process')">
                                                <i class="fa-solid fa-spinner"></i> Mark as In Process
                                            </button>
                                        </li>
                                    @endif
                                    @if($order->status == 'in_process' && in_array($role, ['Admin', 'Warehouse', 'Route']))
                                        <li>
                                            <button type="button" class="dropdown-item" x-data="" x-on:click.prevent="$dispatch('open-modal', 'mark-order-{{ $order->id }}-in-transit')">
                                                <i class="fa-solid fa-truck"></i> Mark as In Transit
                                            </button>
                                        </li>
                                    @endif
                                    @if($order->status == 'in_route' && in_array($role, ['Admin', 'Route']))
                                        <li>
                                            <button type="button" class="dropdown-item" x-data="" x-on:click.prevent="$dispatch('open-modal', 'mark-order-{{ $order->id }}-delivered')">
                                                <i class="fa-solid fa-check"></i> Mark as Delivered
                                            </button>
                                        </li>
                                    @endif
                                    <li><hr class="dropdown-divider"></li>
                                    @if(in_array($role, ['Admin', 'Sales']))
                                    <li>
                                        <form action="{{ route('orders.destroy',$order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="fa-solid fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">There are no data matching your criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{-- Modals for marking orders status transitions --}}
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
            <x-modal name="mark-order-{{ $order->id }}-in-process" focusable>
                <form action="{{ route('orders.markInProcess', $order) }}" method="POST" class="p-6">
                    @csrf
                    <h2 class="text-lg font-medium text-gray-900">Mark Order #{{ $order->id }} as In Process</h2>
                    <div class="mt-4 mb-4">
                        Are you sure you want to mark this order as in process?
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="btn btn-secondary btn-sm me-2" x-on:click="$dispatch('close')">Cancel</button>
                        <button type="submit" class="btn btn-info btn-sm">Mark as In Process</button>
                    </div>
                </form>
            </x-modal>
            <x-modal name="mark-order-{{ $order->id }}-delivered" focusable>
                <form action="{{ route('orders.markDelivered', $order) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    <h2 class="text-lg font-medium text-gray-900">Mark Order #{{ $order->id }} as Delivered</h2>
                    <div class="mt-4 mb-4">
                        <label for="unload_photo_{{ $order->id }}" class="form-label">Unload Photo</label>
                        <input type="file" class="form-control" id="unload_photo_{{ $order->id }}" name="unload_photo" required>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" class="btn btn-secondary btn-sm me-2" x-on:click="$dispatch('close')">Cancel</button>
                        <button type="submit" class="btn btn-success btn-sm">Mark as Delivered</button>
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
