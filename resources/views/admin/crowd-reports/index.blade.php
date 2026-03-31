@extends('layouts.admin')

@section('page_title', 'Crowd Reports')
@section('page_copy', 'Review public crowd feedback submitted by visitors and monitor trends across Hathazari stations.')

@section('page_actions')
    <a href="{{ route('admin.reports.stations.export') }}" class="btn btn-primary rounded-4 px-4 fw-semibold">
        <i class="bi bi-download me-1"></i>Export CSV
    </a>
@endsection

@section('content')
    <div class="table-card">
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div>
                    <h2 class="h4 fw-bold mb-1">Public Crowd Activity</h2>
                    <p class="text-secondary mb-0">Filter and inspect submitted crowd reports by station or crowd level.</p>
                </div>
                <form method="GET" action="{{ route('admin.crowd-reports.index') }}" class="d-flex gap-2 flex-wrap">
                    <input type="text" name="search" value="{{ $search }}" class="form-control rounded-4" placeholder="Search station">
                    <select name="level" class="form-select rounded-4">
                        <option value="">All Levels</option>
                        @foreach ($labels as $key => $label)
                            <option value="{{ $key }}" @selected($level === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control rounded-4">
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control rounded-4">
                    <button type="submit" class="btn btn-outline-secondary rounded-4">Filter</button>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive desktop-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Station</th>
                            <th>Level</th>
                            <th>IP Address</th>
                            <th>Submitted</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $report->station?->name ?? 'Unknown Station' }}</div>
                                    <div class="small text-secondary">{{ $report->station?->location }}</div>
                                </td>
                                <td>{{ $labels[$report->crowd_level] ?? $report->crowd_level }}</td>
                                <td>{{ $report->ip_address }}</td>
                                <td>
                                    <div>{{ $report->created_at->diffForHumans() }}</div>
                                    <div class="small text-secondary">{{ $report->created_at->format('d M Y, h:i A') }}</div>
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.crowd-reports.destroy', $report) }}" onsubmit="return confirm('Delete this crowd report?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-4">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-5">No crowd reports found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-data-list">
                @forelse ($reports as $report)
                    <div class="mobile-station-card">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-bold fs-6">{{ $report->station?->name ?? 'Unknown Station' }}</div>
                                <div class="small text-secondary mt-1">{{ $report->station?->location }}</div>
                            </div>
                            <span class="station-tag">{{ $labels[$report->crowd_level] ?? $report->crowd_level }}</span>
                        </div>

                        <div class="mobile-kv">
                            <div class="mobile-kv-item">
                                <div class="mobile-kv-label">IP Address</div>
                                <div class="fw-semibold">{{ $report->ip_address }}</div>
                            </div>
                            <div class="mobile-kv-item">
                                <div class="mobile-kv-label">Submitted</div>
                                <div class="fw-semibold">{{ $report->created_at->diffForHumans() }}</div>
                                <div class="small text-secondary">{{ $report->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                        </div>

                        <div class="mobile-actions">
                            <form method="POST" action="{{ route('admin.crowd-reports.destroy', $report) }}" class="w-100" onsubmit="return confirm('Delete this crowd report?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger rounded-4 w-100">Delete Report</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-secondary py-5">No crowd reports found.</div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection
