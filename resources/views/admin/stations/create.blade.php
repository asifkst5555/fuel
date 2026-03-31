@extends('layouts.admin')

@section('page_title', 'Add New Station')
@section('page_copy', 'Create a new Hathazari fuel station record and publish its current octane and diesel availability.')

@section('page_actions')
    <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold">
        <i class="bi bi-arrow-left me-1"></i>Back to Stations
    </a>
@endsection

@section('content')
    <div class="form-card">
        <div class="mb-4">
            <h2 class="h3 fw-bold mb-1">Station Information</h2>
            <p class="text-secondary mb-0">Add the station profile, location details, and initial fuel status.</p>
        </div>

        <form method="POST" action="{{ route('admin.stations.store') }}">
            @csrf
            @include('admin.stations._form', ['buttonText' => 'Create Station'])
        </form>
    </div>
@endsection
