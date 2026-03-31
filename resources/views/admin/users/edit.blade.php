@extends('layouts.admin')

@section('page_title', 'Edit User')
@section('page_copy', 'Update role, password, and station assignments for this account.')

@section('page_actions')
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold"><i class="bi bi-arrow-left me-1"></i>Back to Users</a>
@endsection

@section('content')
    <div class="form-card">
        <div class="mb-4">
            <h2 class="h3 fw-bold mb-1">{{ $user->name }}</h2>
            <p class="text-secondary mb-0">Adjust this user's access and assigned stations.</p>
        </div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('admin.users._form', ['buttonText' => 'Update User', 'isEdit' => true])
        </form>
    </div>
@endsection
