@extends('layouts.app')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">User Details</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
        <table class="table table-bordered">
            <tr><th>ID</th><td>{{ $user->id }}</td></tr>
            <tr><th>Name</th><td>{{ $user->name }} {{ $user->second_name }}</td></tr>
            <tr><th>Email</th><td>{{ $user->email }}</td></tr>
            <tr><th>Role</th><td>{{ $user->role->name ?? '' }}</td></tr>
            <tr><th>Created At</th><td>{{ $user->created_at }}</td></tr>
            <tr><th>Updated At</th><td>{{ $user->updated_at }}</td></tr>
        </table>
        <div class="mt-3">
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
            </form>
            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-pen-to-square"></i> Edit</a>
        </div>
    </div>
</div>
@endsection