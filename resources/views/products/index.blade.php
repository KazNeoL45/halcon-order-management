@extends('layouts.app')

@section('content')

<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Products</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @php $role = auth()->user()->role->name; @endphp
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            @if(in_array($role, ['Admin', 'Purchaser']))
                <a class="btn btn-success btn-sm" href="{{ route('products.create') }}">
                    <i class="fa fa-plus"></i> Add New Product
                </a>
            @endif
        </div>

        <table class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <th width="80px">Id</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Description</th>
                    <th width="200px">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                        <form action="{{ route('products.destroy',$product->id) }}" method="POST" class="d-inline-flex">
                            @if(in_array($role, ['Admin', 'Sales', 'Route', 'Warehouse']))
                                <a class="btn btn-info btn-sm me-1" href="{{ route('products.show',$product->id) }}">
                                    <i class="fa-solid fa-list"></i> Show
                                </a>
                            @endif
                            @if(in_array($role, ['Admin', 'Warehouse']))
                                <a class="btn btn-primary btn-sm me-1" href="{{ route('products.edit',$product->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                            @endif
                            @if($role === 'Admin')
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            @endif
                        </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
</div>
@endsection
