@extends('layouts.admin')

@section('page_title', $isAdmin ? 'Station Dashboard' : 'Assigned Station Dashboard')
@section('page_copy', $isAdmin ? 'Monitor Hathazari station inventory, update fuel availability, and keep the public dashboard accurate.' : 'Update only the stations assigned to your account and keep their fuel availability accurate.')

@section('page_actions')
    @if ($isAdmin)
        <a href="{{ route('admin.stations.create') }}" class="btn btn-primary rounded-4 px-4 fw-semibold"><i class="bi bi-plus-circle me-1"></i>Add Station</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary rounded-4 px-4 fw-semibold"><i class="bi bi-person-gear me-1"></i>Manage Users</a>
        <a href="{{ route('admin.reports.stations.export') }}" class="btn btn-outline-secondary rounded-4 px-4 fw-semibold"><i class="bi bi-download me-1"></i>Export CSV</a>
        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-dark rounded-4 px-4 fw-semibold"><i class="bi bi-shield-check me-1"></i>Audit Logs</a>
    @endif
@endsection

@section('content')
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-xxl-3"><div class="summary-card"><div class="summary-label">{{ $isAdmin ? 'Total Stations' : 'Assigned Stations' }}</div><div class="summary-value">{{ $summary['total'] }}</div></div></div>
        <div class="col-sm-6 col-xxl-3"><div class="summary-card"><div class="summary-label">Octane Available</div><div class="summary-value text-success">{{ $summary['octane_available'] }}</div></div></div>
        <div class="col-sm-6 col-xxl-3"><div class="summary-card"><div class="summary-label">Petrol Available</div><div class="summary-value" style="color:#f59e0b;">{{ $summary['petrol_available'] }}</div></div></div>
        <div class="col-sm-6 col-xxl-3"><div class="summary-card"><div class="summary-label">Diesel Available</div><div class="summary-value text-primary">{{ $summary['diesel_available'] }}</div></div></div>
        <div class="col-sm-6 col-xxl-3"><div class="summary-card"><div class="summary-label">Updated Today</div><div class="summary-value" style="color:#ce8a19;">{{ $summary['updated_today'] }}</div></div></div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-5">
            <div class="insight-card">
                <div class="insight-title">Fuel & Crowd Overview</div>
                <div class="insight-copy">{{ $isAdmin ? 'Live activity across the full network.' : 'Only activity for your assigned stations.' }}</div>
                <div class="row g-3 align-items-center mb-3">
                    <div class="col-lg-6"><canvas id="fuelSummaryChart" height="220"></canvas></div>
                    <div class="col-lg-6"><canvas id="crowdSummaryChart" height="220"></canvas></div>
                </div>
                <div class="row g-3">
                    <div class="col-6"><div class="summary-card"><div class="summary-label">Reports Today</div><div class="summary-value">{{ $crowdSummary['reports_today'] }}</div></div></div>
                    <div class="col-6"><div class="summary-card"><div class="summary-label">Low Crowd</div><div class="summary-value text-success">{{ $crowdSummary['low'] }}</div></div></div>
                    <div class="col-6"><div class="summary-card"><div class="summary-label">Medium Crowd</div><div class="summary-value text-warning">{{ $crowdSummary['medium'] }}</div></div></div>
                    <div class="col-6"><div class="summary-card"><div class="summary-label">High / Severe</div><div class="summary-value text-danger">{{ $crowdSummary['high'] + $crowdSummary['severe'] }}</div></div></div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="insight-card">
                <div class="insight-title">Recent Public Feedback</div>
                <div class="insight-copy">Latest crowd updates submitted by visitors{{ $isAdmin ? ' from the public monitoring page.' : ' for your assigned stations.' }}</div>
                @forelse ($recentCrowdReports as $report)
                    <div class="activity-item">
                        <div><div class="fw-bold">{{ $report->station?->name ?? 'Unknown Station' }}</div><div class="small text-secondary">{{ \App\Models\CrowdReport::labels()[$report->crowd_level] ?? $report->crowd_level }}</div></div>
                        <div class="text-end"><div class="small fw-semibold">{{ $report->created_at->diffForHumans() }}</div><div class="small text-secondary">{{ $report->ip_address }}</div></div>
                    </div>
                @empty
                    <div class="text-secondary">No public crowd feedback yet.</div>
                @endforelse
            </div>
        </div>
        <div class="col-xl-6">
            <div class="insight-card">
                <div class="insight-title">Most Active Stations</div>
                <div class="insight-copy">Stations with the highest volume of crowd activity{{ $isAdmin ? '.' : ' in your assignment list.' }}</div>
                <div class="row g-3">
                    @forelse ($topStations as $index => $topStation)
                        <div class="col-md-6"><div class="summary-card"><div class="summary-label">Rank #{{ $index + 1 }}</div><div class="fw-bold mb-1">{{ $topStation->name }}</div><div class="small text-secondary mb-1">{{ $topStation->location }}</div><div class="small text-secondary mb-2">Dealer: {{ $topStation->dealer ?? 'N/A' }}</div><div class="summary-value text-primary" style="font-size:1.5rem;">{{ $topStation->crowd_reports_count }}</div><div class="small text-secondary">public crowd reports</div></div></div>
                    @empty
                        <div class="col-12 text-secondary">No crowd activity ranking available.</div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="insight-card">
                <div class="insight-title">Stale Update Alerts</div>
                <div class="insight-copy">Stations below have not refreshed live fuel status for more than 30 minutes.</div>
                @forelse ($staleStations as $staleStation)
                    <div class="activity-item">
                        <div><div class="fw-bold">{{ $staleStation->name }}</div><div class="small text-secondary">{{ $staleStation->location }}</div><div class="small text-secondary">Dealer: {{ $staleStation->dealer ?? 'N/A' }}</div></div>
                        <div class="text-end"><div class="admin-badge admin-badge-danger"><i class="bi bi-exclamation-triangle-fill"></i>{{ $staleStation->fuelStatus?->updated_at?->diffForHumans() ?? 'Never updated' }}</div></div>
                    </div>
                @empty
                    <div class="text-secondary">All visible stations are updated within the monitoring window.</div>
                @endforelse
            </div>
        </div>
        @if ($isAdmin)
            <div class="col-xl-6">
                <div class="insight-card">
                    <div class="insight-title">Suspicious Crowd Activity</div>
                    <div class="insight-copy">IPs with unusually high submission volume during the last 24 hours.</div>
                    @forelse ($suspiciousIps as $ip)
                        <div class="activity-item"><div><div class="fw-bold">{{ $ip->ip_address }}</div><div class="small text-secondary">{{ $ip->station_count }} stations affected</div></div><div class="text-end"><div class="admin-badge admin-badge-danger"><i class="bi bi-shield-exclamation"></i>{{ $ip->report_count }} reports</div></div></div>
                    @empty
                        <div class="text-secondary">No suspicious reporting spike detected.</div>
                    @endforelse
                </div>
            </div>
            <div class="col-xl-6">
                <div class="insight-card">
                    <div class="insight-title">Recent Admin Activity</div>
                    <div class="insight-copy">Sensitive actions are tracked here for accountability and incident review.</div>
                    @forelse ($recentAuditLogs as $log)
                        <div class="activity-item"><div><div class="fw-bold">{{ $log->description }}</div><div class="small text-secondary">{{ $log->user?->name ?? 'System' }} • {{ $log->action }}</div></div><div class="text-end"><div class="small fw-semibold">{{ $log->created_at->diffForHumans() }}</div><div class="small text-secondary">{{ class_basename((string) $log->target_type) }} #{{ $log->target_id ?? 'N/A' }}</div></div></div>
                    @empty
                        <div class="text-secondary">No admin audit entries yet.</div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>

    <div class="table-card">
        <div class="panel-header">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <div>
                    <h2 class="h4 fw-bold mb-1">{{ $isAdmin ? 'Station Management' : 'Assigned Station Updates' }}</h2>
                    <p class="text-secondary mb-0">{{ $isAdmin ? 'Update live station records, fuel status, and maintenance actions.' : 'You can update fuel status for the stations assigned to your account.' }}</p>
                </div>
                <form method="GET" action="{{ route('admin.stations.index') }}" class="d-flex gap-2 flex-wrap w-100" style="max-width: 420px;">
                    <input type="text" name="search" value="{{ $search }}" class="form-control rounded-4 flex-grow-1" placeholder="Search station, dealer, or location">
                    <button class="btn btn-outline-secondary rounded-4 px-3" type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="panel-body pt-4">
            @if ($stations->count())
                <div class="table-responsive desktop-table">
                    <table class="table align-middle">
                        <thead><tr><th>Station</th><th>Location</th><th>Dealer</th><th>Fuel Status</th><th>Last Updated</th><th class="text-end">Actions</th></tr></thead>
                        <tbody>
                            @foreach ($stations as $station)
                                @php($status = $station->fuelStatus)
                                <tr>
                                    <td style="min-width: 240px;"><div class="fw-bold fs-6">{{ $station->name }}</div><div class="small text-secondary mt-1">Station ID: {{ $station->id }}</div></td>
                                    <td style="min-width: 240px;"><div class="text-secondary">{{ $station->location }}</div></td>
                                    <td style="min-width: 150px;"><div class="fw-semibold">{{ $station->dealer ?? 'N/A' }}</div></td>
                                    <td style="min-width: 420px;">
                                        <form method="POST" action="{{ route('admin.stations.status', $station) }}" class="row g-2 align-items-end">
                                            @csrf @method('PATCH')
                                            <div class="col-12 col-md-3"><label class="form-label small text-secondary fw-semibold mb-1">Octane</label><select name="octane" class="form-select form-select-sm rounded-4 status-select"><option value="1" @selected($status?->octane)>Available</option><option value="0" @selected(! $status?->octane)>Not Available</option></select></div>
                                            <div class="col-12 col-md-3"><label class="form-label small text-secondary fw-semibold mb-1">Petrol</label><select name="petrol" class="form-select form-select-sm rounded-4 status-select"><option value="1" @selected($status?->petrol)>Available</option><option value="0" @selected(! $status?->petrol)>Not Available</option></select></div>
                                            <div class="col-12 col-md-3"><label class="form-label small text-secondary fw-semibold mb-1">Diesel</label><select name="diesel" class="form-select form-select-sm rounded-4 status-select"><option value="1" @selected($status?->diesel)>Available</option><option value="0" @selected(! $status?->diesel)>Not Available</option></select></div>
                                            <div class="col-12 col-md-3 d-flex align-items-end"><button type="submit" class="btn btn-sm btn-primary rounded-4 w-100">Save</button></div>
                                        </form>
                                    </td>
                                    <td style="min-width: 190px;"><div class="fw-semibold">{{ $status?->updated_at?->diffForHumans() ?? 'Not updated yet' }}</div><div class="small text-secondary">{{ $status?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div></td>
                                    <td class="text-end" style="min-width: 170px;"><div class="d-flex justify-content-end gap-2 flex-wrap"><a href="{{ route('admin.stations.show', $station) }}" class="btn btn-sm btn-outline-primary rounded-4">View</a>@if ($isAdmin)<a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-sm btn-outline-dark rounded-4">Edit</a><form method="POST" action="{{ route('admin.stations.destroy', $station) }}" onsubmit="return confirm('Delete this station?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger rounded-4">Delete</button></form>@endif</div></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mobile-station-list">
                    @foreach ($stations as $station)
                        @php($status = $station->fuelStatus)
                        <div class="mobile-station-card">
                            <div class="d-flex justify-content-between align-items-start gap-3"><div><div class="fw-bold fs-5">{{ $station->name }}</div><div class="small text-secondary mt-1">{{ $station->location }}</div><div class="small text-secondary mt-1">Dealer: {{ $station->dealer ?? 'N/A' }}</div></div><span class="station-tag">ID {{ $station->id }}</span></div>
                            <div class="mobile-kv"><div class="mobile-kv-item"><div class="mobile-kv-label">Last Updated</div><div class="fw-semibold">{{ $status?->updated_at?->diffForHumans() ?? 'Not updated yet' }}</div><div class="small text-secondary">{{ $status?->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div></div></div>
                            <form method="POST" action="{{ route('admin.stations.status', $station) }}" class="row g-2 mt-1">
                                @csrf @method('PATCH')
                                <div class="col-4"><label class="form-label small text-secondary fw-semibold mb-1">Octane</label><select name="octane" class="form-select form-select-sm rounded-4"><option value="1" @selected($status?->octane)>Available</option><option value="0" @selected(! $status?->octane)>Not Available</option></select></div>
                                <div class="col-4"><label class="form-label small text-secondary fw-semibold mb-1">Petrol</label><select name="petrol" class="form-select form-select-sm rounded-4"><option value="1" @selected($status?->petrol)>Available</option><option value="0" @selected(! $status?->petrol)>Not Available</option></select></div>
                                <div class="col-4"><label class="form-label small text-secondary fw-semibold mb-1">Diesel</label><select name="diesel" class="form-select form-select-sm rounded-4"><option value="1" @selected($status?->diesel)>Available</option><option value="0" @selected(! $status?->diesel)>Not Available</option></select></div>
                                <div class="col-12"><button type="submit" class="btn btn-primary rounded-4 w-100">Save Fuel Status</button></div>
                            </form>
                            <div class="mobile-actions"><a href="{{ route('admin.stations.show', $station) }}" class="btn btn-outline-primary rounded-4 flex-fill">View</a>@if ($isAdmin)<a href="{{ route('admin.stations.edit', $station) }}" class="btn btn-outline-dark rounded-4 flex-fill">Edit</a><form method="POST" action="{{ route('admin.stations.destroy', $station) }}" class="flex-fill" onsubmit="return confirm('Delete this station?');">@csrf @method('DELETE')<button type="submit" class="btn btn-outline-danger rounded-4 w-100">Delete</button></form>@endif</div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $stations->links() }}</div>
            @else
                <div class="empty-state">
                    <div class="fs-1 mb-3 text-secondary"><i class="bi bi-building"></i></div>
                    <h3 class="h4 fw-bold mb-2">{{ $isAdmin ? 'No stations available' : 'No stations assigned yet' }}</h3>
                    <p class="text-secondary mb-4">{{ $isAdmin ? 'Start by creating a station and setting its octane, petrol, and diesel availability.' : 'Ask an administrator to assign one or more stations to your account.' }}</p>
                    @if ($isAdmin)
                        <a href="{{ route('admin.stations.create') }}" class="btn btn-primary rounded-4 px-4">Create First Station</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        (() => {
            const chartData = @json($chartData);
            const fuelCanvas = document.getElementById('fuelSummaryChart');
            const crowdCanvas = document.getElementById('crowdSummaryChart');
            if (fuelCanvas) {
                new Chart(fuelCanvas, { type: 'doughnut', data: { labels: chartData.fuel.labels, datasets: [{ data: chartData.fuel.values, backgroundColor: ['#16a34a', '#f59e0b', '#2563eb', '#d97706'], borderWidth: 0 }] }, options: { plugins: { legend: { position: 'bottom' } }, cutout: '68%' } });
            }
            if (crowdCanvas) {
                new Chart(crowdCanvas, { type: 'bar', data: { labels: chartData.crowd.labels, datasets: [{ data: chartData.crowd.values, backgroundColor: ['#16a34a', '#f59e0b', '#f97316', '#ef4444'], borderRadius: 10 }] }, options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { precision: 0 } } } } });
            }
        })();
    </script>
@endpush
