@extends('orders.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Halcon Managment</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-filter"></i> Filtros
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="invoice_number" class="form-label">Número de Factura</label>
                            <input type="text" class="form-control form-control-sm" id="invoice_number" name="invoice_number" value="{{ $filterInputs['invoice_number'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="client_id" class="form-label">ID de Cliente</label>
                            <input type="text" class="form-control form-control-sm" id="client_id" name="client_id" value="{{ $filterInputs['client_id'] ?? '' }}">
                            {{-- Si usas client_name:
                            <label for="client_name" class="form-label">Nombre del Cliente</label>
                            <input type="text" class="form-control form-control-sm" id="client_name" name="client_name" value="{{ $filterInputs['client_name'] ?? '' }}">
                            --}}
                        </div>
                        <div class="col-md-3">
                            <label for="order_date" class="form-label">Fecha del Pedido</label>
                            <input type="date" class="form-control form-control-sm" id="order_date" name="order_date" value="{{ $filterInputs['order_date'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select form-select-sm" id="status" name="status">
                                <option value="">Todos</option>
                                <option value="pending" {{ (isset($filterInputs['status']) && $filterInputs['status'] == 'pending') ? 'selected' : '' }}>Pendiente</option>
                                <option value="shipped" {{ (isset($filterInputs['status']) && $filterInputs['status'] == 'shipped') ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ (isset($filterInputs['status']) && $filterInputs['status'] == 'delivered') ? 'selected' : '' }}>Entregado</option>
                      
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Filtrar</button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm"><i class="fa fa-eraser"></i> Limpiar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-center mb-3">
            @if ($orders->total() > 0)
            <div class="text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} out of {{ $orders->total() }} orders.
            </div>
            @else
            <div class="text-muted">
               no orders found.
            </div>
            @endif

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a class="btn btn-success btn-sm" href="{{ route('orders.create') }}">
                <i class="fa fa-plus"></i> Add new order</a>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-bordered table-striped mt-2"> 
                <thead class="table-light"> 
                    <tr>
                        <th width="80px">ID Pedido</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th width="250px" class="text-center">Actions</th> 
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->client->name ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $statusConfig = match(strtolower($order->status)) { // strtolower para consistencia
                                        'pending'   => ['class' => 'badge bg-warning text-dark', 'text' => 'Pendiente'],
                                        'shipped'   => ['class' => 'badge bg-info text-dark', 'text' => 'Enviado'],
                                        'delivered' => ['class' => 'badge bg-success', 'text' => 'Entregado'],
                                        default     => ['class' => 'badge bg-secondary', 'text' => ucfirst($order->status)],
                                    };
                                @endphp
                                <span class="{{ $statusConfig['class'] }} py-1 px-2" style="font-size: 0.85em; width: 100px; display: inline-block; text-align: center;">
                                    {{ $statusConfig['text'] }}
                                </span>
                            </td>
                            <td>{{ number_format($order->total, 2) }}</td> 
                            <td class="text-center"> 
                                <form action="{{ route('orders.destroy',$order->id) }}" method="POST" class="d-inline">
                                    <a class="btn btn-info btn-sm me-1"
                                    href="{{ route('orders.show',$order->id) }}">
                                    <i class="fa-solid fa-list"></i> <span class="d-none d-md-inline">Show</span></a>
                                    <a class="btn btn-primary btn-sm me-1"
                                    href="{{ route('orders.edit',$order->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> <span class="d-none d-md-inline">Edit</span></a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este pedido?');">
                                    <i class="fa-solid fa-trash"></i> <span class="d-none d-md-inline">Delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">
                                <p class="mb-2">No data to show.</p>
                                @if(count(array_filter((array)($filterInputs ?? []))) > 0)
                                   <p> Try different filters or <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">clean filters</a>.</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

      
        @if ($orders->hasPages())
<div class="mt-4 d-flex justify-content-center"> 
    <nav aria-label="Page navigation"> 
        {{-- appends(request()->query()) --}}
        {{ $orders->appends(request()->query())->links() }}
    </nav>
</div>
@endif