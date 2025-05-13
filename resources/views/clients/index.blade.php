@extends('layouts.app')

@section('content')

<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Clients</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a href="{{ route('clients.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Add New Client
            </a>
        </div>

        <table class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Tax Info</th>
                    <th width="200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ $client->email }}</td>
                        <td>{{ $client->phone }}</td>
                        <td>{{ $client->tax_information }}</td>
                        <td>
                            <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Show
                                </a>
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pen-to-square"></i> Edit
                                </a>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">No clients found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $clients->links() }}
    </div>
</div>
@endsection
