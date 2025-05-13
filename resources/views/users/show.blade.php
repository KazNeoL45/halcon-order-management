@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Details</h1>
    <div class="mb-3"><strong>Name:</strong> {{ $user->name }} {{ $user->second_name }}</div>
    <div class="mb-3"><strong>Email:</strong> {{ $user->email }}</div>
    <div class="mb-3"><strong>Role:</strong> {{ $user->role->name ?? '' }}</div>
    <div class="mb-3"><strong>Created At:</strong> {{ $user->created_at }}</div>
    <div class="mb-3"><strong>Updated At:</strong> {{ $user->updated_at }}</div>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection