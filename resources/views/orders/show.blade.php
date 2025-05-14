@extends('layouts.app')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Order: {{$order->id}}</h2>
    <div class="card-body">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <a class="btn btn-primary btn-sm" href="{{ route('orders.index') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Status:</strong> <br/>
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
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Total:</strong> <br/>
                    <span class="d-block">{{ $order->total }}</span>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Client:</strong> <br/>
                    <span class="d-block">{{ $order->client->name }}</span>
                </div>
            </div>
        </div>
        @if($order->address)
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Delivery Address:</strong>
                    <div class="border p-3 rounded mt-2 bg-light">
                        <address class="mb-0">
                            <i class="fa fa-map-marker-alt text-primary me-2"></i>
                            {{ $order->address->street }} {{ $order->address->external_number }}<br>
                            {{ $order->address->colony }}<br>
                            {{ $order->address->city }}, {{ $order->address->state->name }} {{ $order->address->zip_code }}<br>
                            {{ $order->address->country->name }}
                        </address>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Order Items:</strong> <br/>
                </div>
            </div>
        </div>
        <div class="row mt-2">
          <table class="table table-bordered table-striped mt-4">
              <thead>
                  <tr>
                      <th width="80px">ItemId</th>
                      <th>ProductId</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Subtotal</th>
                  </tr>
              </thead>

              <tbody>
                  @forelse ($order->items as $orderItem)
                      <tr>
                          <td>{{ $orderItem->id }}</td>
                          <td>{{ $orderItem->product_id }}</td>
                          <td>{{ $orderItem->product->name }}</td>
                          <td>{{ $orderItem->quantity }}</td>
                          <td>{{ $orderItem->subtotal }}</td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="4">There are no data.</td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
       </div>
        @if($order->load_photo || $order->unload_photo)
            <div class="row mt-4 mb-3">
                @if($order->load_photo)
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <strong>Load Photo:</strong>
                            <div class="mt-2">
                                <a href="{{ route('orders.loadPhoto', $order) }}" target="_blank">
                                    <img src="{{ route('orders.loadPhoto', $order) }}" alt="Load Photo" class="img-fluid img-thumbnail" style="max-height:200px;">
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
                @if($order->unload_photo)
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <strong>Unload Photo:</strong>
                            <div class="mt-2">
                                <a href="{{ route('orders.unloadPhoto', $order) }}" target="_blank">
                                    <img src="{{ route('orders.unloadPhoto', $order) }}" alt="Unload Photo" class="img-fluid img-thumbnail" style="max-height:200px;">
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
