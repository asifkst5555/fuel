@extends('layouts.admin')

@php($crowdLabels = \App\Models\CrowdReport::labels())

@section('page_title', 'Station Detail')
@section('page_copy', 'Detailed station profile, fuel availability, and public crowd activity history.')

@section('page_actions')
    <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold"><i class="bi bi-arrow-left me-1"></i>Back</a>
    @if ($isAdmin)
        <a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-primary rounded-4 px-4 fw-semibold"><i class="bi bi-pencil-square me-1"></i>Edit Station</a>
    @endif
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="insight-card">
                <div class="insight-title">{{ $station->name }}</div>
                <div class="insight-copy">{{ $station->location }}</div>
                <div class="row g-3">
                    <div class="col-6"><div class="summary-card"><div class="summary-label">Octane</div><div class="summary-value {{ $station->fuelStatus?->octane ? 'text-success' : 'text-danger' }}">{{ $station->fuelStatus?->octane ? 'Yes' : 'No' }}</div></div></div>
                    <div class="col-6"><div class="summary-card"><div class="summary-label">Diesel</div><div class="summary-value {{ $station->fuelStatus?->diesel ? 'text-success' : 'text-danger' }}">{{ $station->fuelStatus?->diesel ? 'Yes' : 'No' }}</div></div></div>
                    <div class="col-12"><div class="summary-card"><div class="summary-label">Latest Crowd</div><div class="summary-value" style="font-size:1.3rem;">{{ $crowdLabels[$station->latestCrowdReport?->crowd_level] ?? 'No report yet' }}</div><div class="small text-secondary mt-2">{{ $station->latestCrowdReport?->created_at?->diffForHumans() ?? 'Awaiting public reports' }}</div></div></div>
                    @if ($isAdmin)
                        <div class="col-12"><div class="summary-card"><div class="summary-label">Assigned Users</div>@forelse ($station->users as $user)<div class="small fw-semibold mb-2">{{ $user->name }} <span class="text-secondary">({{ $user->email }})</span></div>@empty<div class="small text-secondary">No station manager assigned yet.</div>@endforelse</div></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="insight-card">
                <div class="insight-title">Recent Crowd Timeline</div>
                <div class="insight-copy">Latest visitor crowd submissions for this station.</div>
                @forelse ($station->crowdReports as $report)
                    <div class="activity-item"><div><div class="fw-bold">{{ $crowdLabels[$report->crowd_level] ?? $report->crowd_level }}</div><div class="small text-secondary">{{ $report->ip_address }}</div></div><div class="small text-secondary text-end">{{ $report->created_at->diffForHumans() }}</div></div>
                @empty
                    <div class="text-secondary">No crowd history available.</div>
                @endforelse
            </div>
        </div>
        <div class="col-12">
            <div class="table-card">
                <div class="panel-header"><h2 class="h4 fw-bold mb-1">Fuel Status Timeline</h2><p class="text-secondary mb-0">Recent fuel status entries recorded for this station.</p></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead><tr><th>Octane</th><th>Diesel</th><th>Updated</th></tr></thead>
                            <tbody>
                                @forelse ($fuelTimeline as $item)
                                    <tr><td>{{ $item->octane ? 'Available' : 'Not Available' }}</td><td>{{ $item->diesel ? 'Available' : 'Not Available' }}</td><td><div>{{ $item->updated_at?->diffForHumans() }}</div><div class="small text-secondary">{{ $item->updated_at?->format('d M Y, h:i A') }}</div></td></tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-secondary py-4">No fuel history available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
