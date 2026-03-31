<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $title ?? 'Hathazari Fuel Monitor Admin' }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <style>
            html { font-size: 80%; }
            :root {
                --admin-bg: #f4f7fb; --admin-surface: #ffffff; --admin-surface-soft: #f8fbff; --admin-border: #e3eaf3;
                --admin-text: #13233d; --admin-soft: #6f8099; --admin-primary: #2448d8; --admin-primary-soft: #eaf0ff;
                --admin-success: #0d9960; --admin-danger: #dd4a4a; --admin-warning: #ce8a19; --admin-shadow: 0 18px 50px rgba(23, 39, 70, 0.08);
            }
            body { margin: 0; font-family: "Inter", sans-serif; color: var(--admin-text); background: radial-gradient(circle at top right, rgba(36, 72, 216, 0.08), transparent 22%), linear-gradient(180deg, #f8fbff 0%, var(--admin-bg) 100%); }
            .admin-app { min-height: 100vh; display: grid; grid-template-columns: 290px minmax(0, 1fr); }
            .admin-sidebar { position: sticky; top: 0; height: 100vh; padding: 26px 20px; background: linear-gradient(180deg, #13294d 0%, #10233f 100%); color: #dbe7ff; }
            .sidebar-brand { display: flex; align-items: center; gap: 14px; text-decoration: none; color: #fff; margin-bottom: 28px; }
            .brand-icon { width: 50px; height: 50px; border-radius: 16px; display: grid; place-items: center; background: linear-gradient(135deg, #35c8b0 0%, #20a391 100%); box-shadow: 0 16px 36px rgba(53, 200, 176, 0.2); font-size: 1.2rem; }
            .sidebar-title { font-size: 1.1rem; font-weight: 800; line-height: 1.15; }
            .sidebar-subtitle { color: #9fb0ce; font-size: 0.9rem; }
            .sidebar-section-title { color: #8ea0bf; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; margin: 24px 0 12px; }
            .sidebar-nav { display: grid; gap: 8px; }
            .sidebar-link { display: flex; align-items: center; gap: 12px; padding: 14px 16px; border-radius: 16px; color: #d6e3ff; text-decoration: none; font-weight: 600; transition: 0.2s ease; }
            .sidebar-link:hover, .sidebar-link.active { color: #fff; background: rgba(255, 255, 255, 0.08); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.06); }
            .sidebar-footer { margin-top: auto; padding-top: 22px; }
            .sidebar-user { border-radius: 20px; padding: 16px; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255,255,255,0.06); }
            .admin-main { padding: 26px; }
            .mobile-admin-topbar { display: none; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 18px; padding: 14px 16px; background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 18px; box-shadow: var(--admin-shadow); }
            .topbar { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 24px; }
            .topbar-title { font-size: 1.9rem; font-weight: 800; line-height: 1.1; margin: 0 0 0.35rem; }
            .topbar-copy { margin: 0; color: var(--admin-soft); }
            .topbar-actions { display: flex; gap: 10px; flex-wrap: wrap; }
            .surface-card, .summary-card, .table-card, .form-card { background: var(--admin-surface); border: 1px solid var(--admin-border); border-radius: 24px; box-shadow: var(--admin-shadow); }
            .summary-card { padding: 20px; height: 100%; }
            .insight-card { background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%); border: 1px solid var(--admin-border); border-radius: 24px; box-shadow: var(--admin-shadow); padding: 22px; height: 100%; }
            .insight-title { font-size: 1.05rem; font-weight: 800; margin-bottom: 0.25rem; }
            .insight-copy { color: var(--admin-soft); font-size: 0.92rem; margin-bottom: 18px; }
            .activity-item { display: flex; justify-content: space-between; gap: 12px; padding: 12px 0; border-top: 1px solid var(--admin-border); }
            .activity-item:first-child { border-top: 0; padding-top: 0; }
            .summary-label { color: var(--admin-soft); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; margin-bottom: 10px; }
            .summary-value { font-size: 2rem; font-weight: 800; line-height: 1; }
            .table-card { overflow: hidden; }
            .desktop-table { display: block; }
            .mobile-station-list { display: none; }
            .table-card .table { margin-bottom: 0; }
            .table-card thead th { color: var(--admin-soft); font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; background: #fbfdff; border-bottom-width: 1px; }
            .table-card td, .table-card th { padding: 18px 20px; vertical-align: middle; border-color: var(--admin-border); }
            .panel-header { padding: 22px 24px 0; }
            .panel-body { padding: 22px 24px 24px; }
            .station-tag { display: inline-flex; align-items: center; gap: 0.45rem; padding: 0.45rem 0.8rem; border-radius: 999px; background: var(--admin-primary-soft); color: var(--admin-primary); font-size: 0.82rem; font-weight: 700; }
            .status-select { min-width: 130px; }
            .empty-state { padding: 60px 20px; text-align: center; }
            .mobile-station-card { border: 1px solid var(--admin-border); border-radius: 20px; background: #fff; padding: 16px; box-shadow: 0 12px 28px rgba(23, 39, 70, 0.05); }
            .mobile-station-card + .mobile-station-card { margin-top: 14px; }
            .mobile-kv { display: grid; gap: 10px; margin-top: 14px; }
            .mobile-kv-item { border-radius: 14px; background: var(--admin-surface-soft); border: 1px solid var(--admin-border); padding: 12px 14px; }
            .mobile-kv-label { color: var(--admin-soft); font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
            .mobile-actions { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 14px; }
            .form-card { padding: 28px; }
            .form-section { border: 1px solid var(--admin-border); border-radius: 22px; background: var(--admin-surface-soft); padding: 20px; }
            .form-section-title { font-size: 1.05rem; font-weight: 800; margin-bottom: 4px; }
            .form-section-copy { color: var(--admin-soft); margin-bottom: 18px; font-size: 0.94rem; }
            .form-actions { position: sticky; bottom: 12px; z-index: 8; margin-top: 18px; padding: 12px; border-radius: 18px; background: rgba(255,255,255,0.94); backdrop-filter: blur(10px); border: 1px solid var(--admin-border); box-shadow: 0 10px 24px rgba(23, 39, 70, 0.08); }
            .form-card .form-control, .form-card .form-select { border-radius: 14px; border-color: var(--admin-border); padding: 0.9rem 1rem; }
            .switch-card { border-radius: 18px; padding: 18px; background: var(--admin-surface-soft); border: 1px solid var(--admin-border); }
            .admin-badge { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 0.8rem; border-radius: 999px; font-weight: 700; font-size: 0.82rem; }
            .admin-badge-success { background: #e8f8f0; color: var(--admin-success); }
            .admin-badge-danger { background: #ffedef; color: var(--admin-danger); }
            .station-checklist { display: grid; gap: 12px; max-height: 420px; overflow-y: auto; padding-right: 6px; }
            .station-checklist::-webkit-scrollbar { width: 10px; }
            .station-checklist::-webkit-scrollbar-thumb { background: #cdd9ec; border-radius: 999px; border: 2px solid transparent; background-clip: padding-box; }
            .station-checklist::-webkit-scrollbar-track { background: transparent; }
            .station-check-item { position: relative; display: block; cursor: pointer; }
            .station-check-input { position: absolute; opacity: 0; pointer-events: none; }
            .station-check-card { display: flex; align-items: flex-start; gap: 14px; padding: 15px 16px; border-radius: 18px; border: 1px solid var(--admin-border); background: #fff; transition: 0.2s ease; }
            .station-check-card:hover { border-color: #b9cae5; box-shadow: 0 10px 22px rgba(23, 39, 70, 0.06); transform: translateY(-1px); }
            .station-check-indicator { width: 22px; height: 22px; border-radius: 7px; border: 1.5px solid #b8c6dc; background: #fff; flex: 0 0 auto; margin-top: 2px; display: grid; place-items: center; color: #fff; transition: 0.2s ease; }
            .station-check-indicator i { font-size: 0.8rem; opacity: 0; transform: scale(0.7); transition: 0.2s ease; }
            .station-check-content { min-width: 0; }
            .station-check-title { font-weight: 700; color: var(--admin-text); margin-bottom: 4px; }
            .station-check-copy { color: var(--admin-soft); font-size: 0.9rem; line-height: 1.45; }
            .station-check-input:checked + .station-check-card { border-color: rgba(36, 72, 216, 0.28); background: linear-gradient(180deg, #f6f9ff 0%, #eef4ff 100%); box-shadow: 0 12px 28px rgba(36, 72, 216, 0.10); }
            .station-check-input:checked + .station-check-card .station-check-indicator { background: linear-gradient(180deg, #2e5bf0 0%, var(--admin-primary) 100%); border-color: var(--admin-primary); box-shadow: 0 8px 18px rgba(36, 72, 216, 0.22); }
            .station-check-input:checked + .station-check-card .station-check-indicator i { opacity: 1; transform: scale(1); }
            .station-check-input:focus-visible + .station-check-card { outline: 0; box-shadow: 0 0 0 0.2rem rgba(36, 72, 216, 0.16); border-color: rgba(36, 72, 216, 0.35); }
            .station-check-summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; padding: 12px 14px; border-radius: 16px; border: 1px solid var(--admin-border); background: rgba(255,255,255,0.72); }
            .station-check-meta { color: var(--admin-soft); font-size: 0.86rem; }
            @media (max-width: 991.98px) { .admin-app { grid-template-columns: 1fr; } .admin-sidebar { display: none !important; } .mobile-admin-topbar { display: flex; } .admin-main { padding: 16px; } .topbar { flex-direction: column; align-items: flex-start; margin-bottom: 18px; } .topbar-actions { width: 100%; } .topbar-actions > * { flex: 1 1 auto; } .panel-header, .panel-body, .form-card { padding-left: 16px; padding-right: 16px; } }
            @media (max-width: 767.98px) { .topbar-title { font-size: 1.45rem; } .topbar-copy { font-size: 0.94rem; } .summary-card { padding: 16px; } .summary-value { font-size: 1.55rem; } .table-card td, .table-card th { padding: 14px 12px; } .desktop-table { display: none; } .mobile-station-list { display: block; } .form-card { padding-top: 20px; padding-bottom: 20px; } .form-actions { bottom: 8px; } }
        </style>
    </head>
    <body>
        @php($adminUser = auth()->user())
        <div class="admin-app">
            <aside class="admin-sidebar d-flex flex-column">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <div class="brand-icon"><i class="bi bi-fuel-pump-fill"></i></div>
                    <div>
                        <div class="sidebar-title">Hathazari Fuel Monitor</div>
                        <div class="sidebar-subtitle">{{ $adminUser->isAdmin() ? 'Admin Control Panel' : 'Station Manager Panel' }}</div>
                    </div>
                </a>
                <div class="sidebar-section-title">Management</div>
                <nav class="sidebar-nav">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.stations.index') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a>
                    @if ($adminUser->isAdmin())
                        <a href="{{ route('admin.stations.create') }}" class="sidebar-link {{ request()->routeIs('admin.stations.create') ? 'active' : '' }}"><i class="bi bi-plus-circle-fill"></i>Add Station</a>
                    @endif
                    <a href="{{ route('admin.stations.index') }}" class="sidebar-link {{ request()->routeIs('admin.stations.edit') ? 'active' : '' }}"><i class="bi bi-pencil-square"></i>{{ $adminUser->isAdmin() ? 'Manage Stations' : 'Assigned Stations' }}</a>
                    @if ($adminUser->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-person-gear"></i>Users & Assignments</a>
                        <a href="{{ route('admin.crowd-reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.crowd-reports.*') ? 'active' : '' }}"><i class="bi bi-people-fill"></i>Crowd Reports</a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="sidebar-link {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}"><i class="bi bi-shield-check"></i>Audit Logs</a>
                        <a href="{{ route('admin.reports.stations.export') }}" class="sidebar-link"><i class="bi bi-download"></i>Export CSV</a>
                    @endif
                    <a href="{{ route('home') }}" target="_blank" class="sidebar-link"><i class="bi bi-box-arrow-up-right"></i>View Public Site</a>
                </nav>
                <div class="sidebar-footer">
                    <div class="sidebar-user mb-3">
                        <div class="fw-bold text-white">{{ $adminUser->name }}</div>
                        <div class="small text-white-50">{{ $adminUser->email }}</div>
                        <div class="small text-white-50 mt-1">{{ $adminUser->isAdmin() ? 'Administrator' : 'Station Manager' }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="btn btn-light w-100 rounded-4 fw-semibold">Logout</button></form>
                </div>
            </aside>
            <main class="admin-main">
                <div class="mobile-admin-topbar">
                    <div>
                        <div class="fw-bold">{{ $adminUser->isAdmin() ? 'Hathazari Admin' : 'Station Manager' }}</div>
                        <div class="small text-secondary">{{ $adminUser->email }}</div>
                    </div>
                    <button class="btn btn-outline-primary rounded-4" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarDrawer" aria-controls="adminSidebarDrawer"><i class="bi bi-list fs-5"></i></button>
                </div>
                <div class="topbar">
                    <div>
                        <h1 class="topbar-title">@yield('page_title', 'Admin Dashboard')</h1>
                        <p class="topbar-copy">@yield('page_copy', 'Manage station records, status updates, and public monitoring data from one place.')</p>
                    </div>
                    <div class="topbar-actions">@yield('page_actions')</div>
                </div>
                @if (session('status'))
                    <div class="alert alert-success border-0 rounded-4 shadow-sm mb-4"><i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebarDrawer" aria-labelledby="adminSidebarDrawerLabel">
            <div class="offcanvas-header border-bottom">
                <div>
                    <h5 class="offcanvas-title fw-bold mb-0" id="adminSidebarDrawerLabel">Hathazari Fuel Monitor</h5>
                    <div class="small text-secondary">{{ $adminUser->isAdmin() ? 'Admin Control Panel' : 'Station Manager Panel' }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('dashboard') || request()->routeIs('admin.stations.index') ? 'active' : '' }}"><i class="bi bi-grid-1x2-fill me-2"></i>Dashboard</a>
                    @if ($adminUser->isAdmin())
                        <a href="{{ route('admin.stations.create') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('admin.stations.create') ? 'active' : '' }}"><i class="bi bi-plus-circle-fill me-2"></i>Add Station</a>
                    @endif
                    <a href="{{ route('admin.stations.index') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('admin.stations.edit') ? 'active' : '' }}"><i class="bi bi-pencil-square me-2"></i>{{ $adminUser->isAdmin() ? 'Manage Stations' : 'Assigned Stations' }}</a>
                    @if ($adminUser->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="bi bi-person-gear me-2"></i>Users & Assignments</a>
                        <a href="{{ route('admin.crowd-reports.index') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('admin.crowd-reports.*') ? 'active' : '' }}"><i class="bi bi-people-fill me-2"></i>Crowd Reports</a>
                        <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-outline-primary rounded-4 text-start {{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}"><i class="bi bi-shield-check me-2"></i>Audit Logs</a>
                        <a href="{{ route('admin.reports.stations.export') }}" class="btn btn-outline-primary rounded-4 text-start"><i class="bi bi-download me-2"></i>Export CSV</a>
                    @endif
                    <a href="{{ route('home') }}" target="_blank" class="btn btn-outline-secondary rounded-4 text-start"><i class="bi bi-box-arrow-up-right me-2"></i>View Public Site</a>
                </div>
                <div class="border rounded-4 p-3 mt-4 bg-light">
                    <div class="fw-bold">{{ $adminUser->name }}</div>
                    <div class="small text-secondary">{{ $adminUser->email }}</div>
                    <div class="small text-secondary mt-1">{{ $adminUser->isAdmin() ? 'Administrator' : 'Station Manager' }}</div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf<button type="submit" class="btn btn-dark rounded-4 w-100">Logout</button></form>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
