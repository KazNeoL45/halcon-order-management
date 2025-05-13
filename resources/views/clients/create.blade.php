@extends('layouts.app')

@section('content')
<div class="card mt-5">
    <h2 class="card-header">Create New Client</h2>
    <div class="card-body">
        <a href="{{ route('clients.index') }}" class="btn btn-primary btn-sm mb-3">
            <i class="fa fa-arrow-left"></i> Back
        </a>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label"><strong>Name</strong></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label"><strong>Email</strong></label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label"><strong>Phone</strong></label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="mb-3">
                <label for="tax_information" class="form-label"><strong>Tax Information</strong></label>
                <input type="text" name="tax_information" id="tax_information" class="form-control" value="{{ old('tax_information') }}">
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save"></i> Create
            </button>
        </form>
    </div>
</div>
@endsection
