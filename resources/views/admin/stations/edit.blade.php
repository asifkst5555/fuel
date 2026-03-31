@extends('layouts.admin')

@section('page_title', 'Edit Station')
@section('page_copy', 'Modify station details, update live fuel status, and keep the public dashboard in sync.')

@section('page_actions')
    <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold">
        <i class="bi bi-arrow-left me-1"></i>Back to Stations
    </a>
@endsection

@section('content')
    <div class="form-card">
        <div class="mb-4">
            <h2 class="h3 fw-bold mb-1">{{ $station->name }}</h2>
            <p class="text-secondary mb-0">Edit the station profile and update current octane or diesel availability.</p>
        </div>

        <form method="POST" action="{{ route('admin.stations.update', $station) }}">
            @csrf
            @method('PUT')
            @include('admin.stations._form', ['buttonText' => 'Update Station'])
        </form>
    </div>
@endsection
