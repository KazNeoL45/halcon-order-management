@extends('products.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Edit Product</h2>
    <div class="card-body">

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary btn-sm" href="{{ route('products.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>

        <form action="{{ route('products.update',$product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3 d-flex gap-3">
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Product name:</strong></label>
                  <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ $product->name }}"
                    id="inputName"
                    placeholder="Name">
                </div>
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Price:</strong></label>
                <input
                    type="number"
                    name="price"
                    class="form-control"
                    value="{{ $product->price }}"
                    id="inputPrice"
                    placeholder="Price">
                </div>
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Initial Stock:</strong></label>
                <input
                    type="number"
                    name="stock"
                    class="form-control"
                    value="{{ $product->stock }}"
                    id="inputPrice"
                    placeholder="Initial Stock">
                </div>
            </div>

            <div class="mb-3">
                <label for="inputcontent" class="form-label"><strong>Description:</strong></label>
                <textarea
                    class="form-control @error('content') is-invalid @enderror"
                    style="height:150px"
                    name="description"
                    id="inputcontent"
                    placeholder="Hi-Fi Music Reproducer"></textarea>
                @error('content')
                    <div class="form-text text-danger">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-success"><i class="fa-solid fa-floppy-disk"></i> Update</button>
        </form>

    </div>
</div>
@endsection
