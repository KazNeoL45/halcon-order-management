@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Users</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">New User</a>
</div>

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->name }} {{ $user->second_name ?? '' }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role->name ?? '' }}</td>
                <td>
                    <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No users found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection