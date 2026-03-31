@extends('layouts.admin')

@section('page_title', 'Audit Logs')
@section('page_copy', 'Track administrative actions, review accountability, and investigate operational changes across the control panel.')

@section('page_actions')
    <a href="{{ route('admin.crowd-reports.index') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold">
        <i class="bi bi-people me-1"></i>Crowd Reports
    </a>
    <a href="{{ route('admin.stations.index') }}" class="btn btn-primary rounded-4 px-4 fw-semibold">
        <i class="bi bi-grid-1x2-fill me-1"></i>Dashboard
    </a>
@endsection

@section('content')
    <div class="table-card">
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div>
                    <h2 class="h4 fw-bold mb-1">Administrative Audit Trail</h2>
                    <p class="text-secondary mb-0">Search by user, action, or description to trace critical platform changes.</p>
                </div>
                <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="d-flex gap-2 flex-wrap">
                    <input type="text" name="search" value="{{ $search }}" class="form-control rounded-4" placeholder="Search user or description">
                    <select name="action" class="form-select rounded-4">
                        <option value="">All Actions</option>
                        @foreach ($actions as $actionOption)
                            <option value="{{ $actionOption }}" @selected($action === $actionOption)>{{ $actionOption }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-outline-secondary rounded-4">Filter</button>
                </form>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive desktop-table">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Admin User</th>
                            <th>Target</th>
                            <th>Description</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td><span class="station-tag">{{ $log->action }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $log->user?->name ?? 'System' }}</div>
                                    <div class="small text-secondary">{{ $log->user?->email ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ class_basename((string) $log->target_type) ?: 'General' }}</div>
                                    <div class="small text-secondary">ID: {{ $log->target_id ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div>{{ $log->description }}</div>
                                    @if ($log->metadata)
                                        <div class="small text-secondary mt-1">{{ json_encode($log->metadata, JSON_UNESCAPED_UNICODE) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $log->created_at->diffForHumans() }}</div>
                                    <div class="small text-secondary">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-5">No audit entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-data-list">
                @forelse ($logs as $log)
                    <div class="mobile-station-card">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <div class="fw-bold fs-6">{{ $log->description }}</div>
                                <div class="small text-secondary mt-1">{{ $log->user?->name ?? 'System' }} - {{ $log->user?->email ?? 'N/A' }}</div>
                            </div>
                            <span class="station-tag">{{ $log->action }}</span>
                        </div>

                        <div class="mobile-kv">
                            <div class="mobile-kv-item">
                                <div class="mobile-kv-label">Target</div>
                                <div class="fw-semibold">{{ class_basename((string) $log->target_type) ?: 'General' }}</div>
                                <div class="small text-secondary">ID: {{ $log->target_id ?? 'N/A' }}</div>
                            </div>
                            <div class="mobile-kv-item">
                                <div class="mobile-kv-label">Time</div>
                                <div class="fw-semibold">{{ $log->created_at->diffForHumans() }}</div>
                                <div class="small text-secondary">{{ $log->created_at->format('d M Y, h:i A') }}</div>
                            </div>
                            @if ($log->metadata)
                                <div class="mobile-kv-item">
                                    <div class="mobile-kv-label">Metadata</div>
                                    <div class="small text-secondary">{{ json_encode($log->metadata, JSON_UNESCAPED_UNICODE) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-secondary py-5">No audit entries found.</div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
