@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User</h1>
    <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>Second Name</label>
            <input type="text" name="second_name" class="form-control" value="{{ old('second_name', $user->second_name) }}">
            @error('second_name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role_id" class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="password" class="form-control">
            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection