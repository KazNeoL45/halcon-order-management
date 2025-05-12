@extends('orders.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Halcon Managment</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-success btn-sm" href="{{ route('orders.create') }}">
            <i class="fa fa-plus"></i>Add New order</a>
        </div>

        <table class="table table-bordered table-striped mt-4">
            <thead>
                <tr>
                    <th width="80px">OrderId</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th width="230px"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->client->name }}</td>
                        <td>
                         @php
                             $statusClass = match($order->status) {
                                 'pending' => 'badge bg-warning text-dark',
                                 'paid' => 'badge bg-primary',
                                 'shipped' => 'badge bg-info text-dark',
                                 'delivered' => 'badge bg-success',
                                 default => 'badge bg-secondary',
                             };
                         @endphp
                         <p class="{{ $statusClass }} d-flex flex-row justify-content-center
                         lead font-weight-bold align-items-center">
                             {{ ucfirst($order->status) }}
                         </p>
                        </td>
                        <td>{{ $order->total }}</td>
                        <td class="d-flex flex-row-reverse">
                            <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                                <a class="btn btn-info btn-sm"
                                href="{{ route('orders.show',$order->id) }}">
                                <i class="fa-solid fa-list">
                                </i> Show</a>
                                <a class="btn btn-primary btn-sm"
                                href="{{ route('orders.edit',$order->id) }}">
                                <i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa-solid fa-trash"></i>
                                    Delete
                                </button>
                            </form>
                        </td>
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
@endsection
