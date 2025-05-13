@extends('layouts.app')

@section('content')
<div class="card mt-1 max-w-7xl mx-auto">
    <h2 class="card-header">Users</h2>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                <i class="fa fa-plus"></i> Add New User
            </a>
        </div>

        <table class="table table-bordered table-striped mt-2">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="200px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }} {{ $user->second_name ?? '' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->name ?? '' }}</td>
                        <td>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-info me-1">
                                <i class="fa-solid fa-list"></i> Show
                            </a>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary me-1">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
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

        {{-- Pagination links (if applicable) --}}
        {{-- {{ $users->links() }} --}}
    </div>
</div>
@endsection