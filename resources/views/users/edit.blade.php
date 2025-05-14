@extends('layouts.app')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Edit User</h2>
    <div class="card-body">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-sm">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </div>
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label"><strong>Name</strong></label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}">
            @error('name')<div class="form-text text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="second_name" class="form-label"><strong>Second Name</strong></label>
            <input type="text" name="second_name" id="second_name" class="form-control" value="{{ old('second_name', $user->second_name) }}">
            @error('second_name')<div class="form-text text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"><strong>Email</strong></label>
            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
            @error('email')<div class="form-text text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label"><strong>Role</strong></label>
            <select name="role_id" id="role_id" class="form-control">
                <option value="">-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
            @error('role_id')<div class="form-text text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label"><strong>New Password (leave blank to keep current)</strong></label>
            <input type="password" name="password" id="password" class="form-control">
            @error('password')<div class="form-text text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label"><strong>Confirm Password</strong></label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">
            <i class="fa fa-save"></i> Save
        </button>
    </form>
    </div>
</div>
@endsection
