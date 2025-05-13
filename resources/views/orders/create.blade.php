@extends('orders.layout')

@section('content')

<div class="card mt-5">
    <h2 class="card-header">Create a new Order</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a class="btn btn-primary btn-sm" href="{{ route('orders.index') }}">
            <i class="fa fa-arrow-left"></i>Back</a>
        </div>

        <form action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="mb-3 d-flex gap-3">
              <div class="d-flex flex-column flex-grow-1">
                <label for="client_id" class="form-label"><strong>Client:</strong></label>
                <select name="client_id" id="client_id" class="form-control">
                  <option value="" disabled selected>Select a client</option>
                  @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                  @endforeach
                </select>
              </div>
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Status:</strong></label>
                <input
                    type="text"
                    name="status"
                    class="form-control"
                    id="inputStatus"
                    placeholder="Status">
              </div>
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>Total:</strong></label>
                <input
                    type="text"
                    name="total"
                    class="form-control"
                    id="Inputtotal"
                    placeholder="Total">
              </div>
              <div clas="d-flex flex-column flex-grow-1">
                <label for="inputName" class="form-label"><strong>
                    Items
                </strong>
                </label>
                <input
                    type="text"
                    name="items"
                    class="form-control"
                    id="Inputitems"
                    placeholder="Fill with items">
              </div>
            </div>

            <div class="mb-3">
              <div class="dropdown">
                <button
                    class="btn btn-secondary dropdown-toggle"
                    type="button"
                    id="dropdownMenuButton"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                  List order items
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="#">Item1</a>
                  <a class="dropdown-item" href="#">Item2</a>
                  <a class="dropdown-item" href="#">Item3</a>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success">
            <i class="fa-solid fa-floppy-disk"></i>
                Submit
            </button>
        </form>
    </div>
</div>
@endsection
