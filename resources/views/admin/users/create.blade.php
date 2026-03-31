@extends('layouts.admin')

@section('page_title', 'Add User')
@section('page_copy', 'Create a new admin or station manager account and assign its stations.')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold"><i class="bi bi-arrow-left me-1"></i>Back to Users</a>
@endsection

@section('content')
    <div class="form-card">
        <div class="mb-4">
            <h2 class="h3 fw-bold mb-1">New User</h2>
            <p class="text-secondary mb-0">Admins can manage everything. Station managers can only update their assigned stations.</p>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form', ['buttonText' => 'Create User', 'isEdit' => false])
        </form>
    </div>
@endsection
