@extends('clients.layout')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Client Details</h2>
    <div class="card-body">
        <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm mb-3">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        <table class="table table-bordered">
            <tr><th>ID</th><td>{{ $client->id }}</td></tr>
            <tr><th>Name</th><td>{{ $client->name }}</td></tr>
            <tr><th>Email</th><td>{{ $client->email }}</td></tr>
            <tr><th>Phone</th><td>{{ $client->phone }}</td></tr>
            <tr><th>Tax Information</th><td>{{ $client->tax_information }}</td></tr>
            <tr><th>Created At</th><td>{{ $client->created_at }}</td></tr>
            <tr><th>Updated At</th><td>{{ $client->updated_at }}</td></tr>
        </table>
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">
                <i class="fa fa-trash"></i> Delete
            </button>
        </form>
        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
            <i class="fa fa-pen-to-square"></i> Edit
        </a>
    </div>
</div>
@endsection
