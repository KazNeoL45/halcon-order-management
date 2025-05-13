@extends('layouts.app')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Show Product</h2>
    <div class="card-body">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-4">
            <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Name:</strong> <br/>
                    <span class="d-block">{{ $product->name }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Price:</strong> <br/>
                    <span class="d-block">{{ number_format($product->price, 2) }} USD</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Stock:</strong> <br/>
                    <span class="d-block">{{ $product->stock }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Description:</strong> <br/>
                    <span class="d-block">{{ $product->description }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
