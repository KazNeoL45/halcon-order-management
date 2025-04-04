@extends('orders.layout')

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
                    <span class="d-block">{{ $order->status }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Total:</strong> <br/>
                    <span class="d-block">{{ $order->total }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>User:</strong> <br/>
                    <span class="d-block">{{ $order->user->name }}</span>
                </div>
            </div>
        </div>

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
    </div>
</div>
@endsection
