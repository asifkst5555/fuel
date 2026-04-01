<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStationRequest;
use App\Http\Requests\UpdateFuelStatusRequest;
use App\Http\Requests\UpdateStationRequest;
use App\Models\AdminAuditLog;
use App\Models\CrowdReport;
use App\Models\FuelStatus;
use App\Models\Station;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StationController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');
        $stationIds = $this->accessibleStationIds();

        $stations = $this->accessibleStationsQuery()
            ->with('fuelStatus')
            ->when($search, function ($query, $search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('dealer', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $summary = [
            'total' => $stationIds->count(),
            'octane_available' => FuelStatus::query()->whereIn('station_id', $stationIds)->where('octane', true)->count(),
            'petrol_available' => FuelStatus::query()->whereIn('station_id', $stationIds)->where('petrol', true)->count(),
            'diesel_available' => FuelStatus::query()->whereIn('station_id', $stationIds)->where('diesel', true)->count(),
            'updated_today' => FuelStatus::query()->whereIn('station_id', $stationIds)->whereDate('updated_at', today())->count(),
        ];

        $crowdSummary = [
            'reports_today' => CrowdReport::query()->whereIn('station_id', $stationIds)->whereDate('created_at', today())->count(),
            'low' => CrowdReport::query()->whereIn('station_id', $stationIds)->where('crowd_level', CrowdReport::LEVEL_LOW)->count(),
            'medium' => CrowdReport::query()->whereIn('station_id', $stationIds)->where('crowd_level', CrowdReport::LEVEL_MEDIUM)->count(),
            'high' => CrowdReport::query()->whereIn('station_id', $stationIds)->where('crowd_level', CrowdReport::LEVEL_HIGH)->count(),
            'severe' => CrowdReport::query()->whereIn('station_id', $stationIds)->where('crowd_level', CrowdReport::LEVEL_SEVERE)->count(),
        ];

        $recentCrowdReports = CrowdReport::query()
            ->with('station')
            ->whereIn('station_id', $stationIds)
            ->latest()
            ->take(8)
            ->get();

        $topStations = $this->accessibleStationsQuery()
            ->withCount('crowdReports')
            ->orderByDesc('crowd_reports_count')
            ->take(5)
            ->get();

        $staleStations = $this->accessibleStationsQuery()
            ->with('fuelStatus')
            ->whereHas('fuelStatus', fn (Builder $query) => $query->where('updated_at', '<=', now()->subMinutes(30)))
            ->get()
            ->sortBy(fn (Station $station) => $station->fuelStatus?->updated_at)
            ->take(6);

        $suspiciousIps = $this->isAdmin()
            ? CrowdReport::query()
                ->selectRaw('ip_address, COUNT(*) as report_count, COUNT(DISTINCT station_id) as station_count')
                ->whereNotNull('ip_address')
                ->where('created_at', '>=', now()->subDay())
                ->groupBy('ip_address')
                ->havingRaw('COUNT(*) >= 3')
                ->orderByDesc('report_count')
                ->take(6)
                ->get()
            : collect();

        $recentAuditLogs = $this->isAdmin()
            ? AdminAuditLog::query()->with('user')->latest()->take(8)->get()
            : collect();

        $chartData = [
            'fuel' => [
                'labels' => ['Octane', 'Petrol', 'Diesel', 'Updated Today'],
                'values' => [$summary['octane_available'], $summary['petrol_available'], $summary['diesel_available'], $summary['updated_today']],
            ],
            'crowd' => [
                'labels' => ['Low', 'Medium', 'High', 'Severe'],
                'values' => [$crowdSummary['low'], $crowdSummary['medium'], $crowdSummary['high'], $crowdSummary['severe']],
            ],
        ];

        return view('admin.stations.index', compact(
            'stations',
            'summary',
            'crowdSummary',
            'recentCrowdReports',
            'chartData',
            'topStations',
            'staleStations',
            'suspiciousIps',
            'recentAuditLogs',
            'search',
            'dateFrom',
            'dateTo'
        ))->with('isAdmin', $this->isAdmin());
    }

    public function show(Station $station): View
    {
        $this->authorizeStationAccess($station);

        $station->load([
            'fuelStatus',
            'latestCrowdReport',
            'users',
            'crowdReports' => fn ($query) => $query->latest()->take(15),
        ]);

        $fuelTimeline = FuelStatus::query()
            ->where('station_id', $station->id)
            ->latest('updated_at')
            ->take(10)
            ->get();

        return view('admin.stations.show', compact('station', 'fuelTimeline'))
            ->with('isAdmin', $this->isAdmin());
    }

    public function crowdReports(): View
    {
        $this->ensureAdmin();

        $level = request('level');
        $search = request('search');
        $dateFrom = request('date_from');
        $dateTo = request('date_to');

        $reports = CrowdReport::query()
            ->with('station')
            ->when($level, fn (Builder $query, string $level) => $query->where('crowd_level', $level))
            ->when($search, function (Builder $query, string $search): void {
                $query->whereHas('station', function (Builder $stationQuery) use ($search): void {
                    $stationQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('dealer', 'like', '%'.$search.'%');
                });
            })
            ->when($dateFrom, fn (Builder $query, string $dateFrom) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn (Builder $query, string $dateTo) => $query->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.crowd-reports.index', [
            'reports' => $reports,
            'level' => $level,
            'search' => $search,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'labels' => CrowdReport::labels(),
        ]);
    }

    public function auditLogs(): View
    {
        $this->ensureAdmin();

        $search = request('search');
        $action = request('action');

        $logs = AdminAuditLog::query()
            ->with('user')
            ->when($action, fn (Builder $query, string $action) => $query->where('action', $action))
            ->when($search, function (Builder $query, string $search): void {
                $query->where(function (Builder $innerQuery) use ($search): void {
                    $innerQuery
                        ->where('description', 'like', '%'.$search.'%')
                        ->orWhere('target_type', 'like', '%'.$search.'%')
                        ->orWhereHas('user', function (Builder $userQuery) use ($search): void {
                            $userQuery
                                ->where('name', 'like', '%'.$search.'%')
                                ->orWhere('email', 'like', '%'.$search.'%');
                        });
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'search' => $search,
            'action' => $action,
            'actions' => AdminAuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
        ]);
    }

    public function destroyCrowdReport(CrowdReport $crowdReport): RedirectResponse
    {
        $this->ensureAdmin();

        $stationName = $crowdReport->station?->name;
        $crowdLevel = $crowdReport->crowd_level;
        $targetId = $crowdReport->id;

        $crowdReport->delete();

        $this->logAdminAction(
            action: 'crowd-report.deleted',
            description: 'Deleted a public crowd feedback entry.',
            targetType: CrowdReport::class,
            targetId: $targetId,
            metadata: [
                'station_name' => $stationName,
                'crowd_level' => $crowdLevel,
            ],
        );

        return back()->with('status', 'Crowd report deleted successfully.');
    }

    public function exportStations(): StreamedResponse
    {
        $this->ensureAdmin();

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Station Name', 'Location', 'Dealer', 'Octane', 'Petrol', 'Diesel', 'Latest Crowd', 'Fuel Updated At']);

            Station::query()
                ->with(['fuelStatus', 'latestCrowdReport'])
                ->orderBy('name')
                ->get()
                ->each(function (Station $station) use ($handle): void {
                    fputcsv($handle, [
                        $station->name,
                        $station->location,
                        $station->dealer ?? 'N/A',
                        $station->fuelStatus?->octane ? 'Yes' : 'No',
                        $station->fuelStatus?->petrol ? 'Yes' : 'No',
                        $station->fuelStatus?->diesel ? 'Yes' : 'No',
                        CrowdReport::labels()[$station->latestCrowdReport?->crowd_level] ?? 'N/A',
                        $station->fuelStatus?->updated_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    ]);
                });

            fclose($handle);
        }, 'hathazari-stations-report.csv', ['Content-Type' => 'text/csv']);
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('admin.stations.create', [
            'station' => new Station(),
        ]);
    }

    public function store(StoreStationRequest $request): RedirectResponse
    {
        $this->ensureAdmin();

        $station = Station::create($request->safe()->only(['name', 'location', 'dealer']));

        $station->fuelStatus()->create([
            'octane' => $request->boolean('octane'),
            'petrol' => $request->boolean('petrol'),
            'diesel' => $request->boolean('diesel'),
        ]);

        $this->logAdminAction(
            action: 'station.created',
            description: 'Created a new station and initial fuel status.',
            target: $station,
            metadata: [
                'name' => $station->name,
                'location' => $station->location,
                'dealer' => $station->dealer,
                'octane' => $request->boolean('octane'),
                'petrol' => $request->boolean('petrol'),
                'diesel' => $request->boolean('diesel'),
            ],
        );

        return redirect()
            ->route('admin.stations.index')
            ->with('status', 'Station created successfully.');
    }

    public function edit(Station $station): View
    {
        $this->ensureAdmin();

        $station->load('fuelStatus');

        return view('admin.stations.edit', compact('station'));
    }

    public function update(UpdateStationRequest $request, Station $station): RedirectResponse
    {
        $this->ensureAdmin();

        $before = $station->only(['name', 'location', 'dealer']);

        $station->update($request->safe()->only(['name', 'location', 'dealer']));

        $station->fuelStatus()->updateOrCreate(
            ['station_id' => $station->id],
            [
                'octane' => $request->boolean('octane'),
                'petrol' => $request->boolean('petrol'),
                'diesel' => $request->boolean('diesel'),
            ]
        );

        $this->logAdminAction(
            action: 'station.updated',
            description: 'Updated station profile and fuel availability.',
            target: $station,
            metadata: [
                'before' => $before,
                'after' => [
                    'name' => $station->name,
                    'location' => $station->location,
                    'dealer' => $station->dealer,
                    'octane' => $request->boolean('octane'),
                    'petrol' => $request->boolean('petrol'),
                    'diesel' => $request->boolean('diesel'),
                ],
            ],
        );

        return redirect()
            ->route('admin.stations.index')
            ->with('status', 'Station updated successfully.');
    }

    public function updateStatus(UpdateFuelStatusRequest $request, Station $station): RedirectResponse
    {
        $this->authorizeStationAccess($station);

        $previousStatus = [
            'octane' => (bool) $station->fuelStatus?->octane,
            'petrol' => (bool) $station->fuelStatus?->petrol,
            'diesel' => (bool) $station->fuelStatus?->diesel,
        ];

        $station->fuelStatus()->updateOrCreate(
            ['station_id' => $station->id],
            $request->validated()
        );

        $this->logAdminAction(
            action: 'station.status-updated',
            description: 'Updated live fuel availability.',
            target: $station,
            metadata: [
                'before' => $previousStatus,
                'after' => $request->validated(),
            ],
        );

        return redirect()
            ->route('admin.stations.index')
            ->with('status', 'Fuel status updated successfully.');
    }

    public function destroy(Station $station): RedirectResponse
    {
        $this->ensureAdmin();

        $targetId = $station->id;
        $snapshot = [
            'name' => $station->name,
            'location' => $station->location,
            'dealer' => $station->dealer,
        ];

        $station->delete();

        $this->logAdminAction(
            action: 'station.deleted',
            description: 'Deleted a station record.',
            targetType: Station::class,
            targetId: $targetId,
            metadata: $snapshot,
        );

        return redirect()
            ->route('admin.stations.index')
            ->with('status', 'Station deleted successfully.');
    }

    protected function accessibleStationsQuery(): Builder
    {
        $query = Station::query();

        if (! $this->isAdmin()) {
            $query->whereHas('users', fn (Builder $builder) => $builder->where('users.id', Auth::id()));
        }

        return $query;
    }

    protected function accessibleStationIds(): Collection
    {
        return $this->accessibleStationsQuery()->pluck('stations.id');
    }

    protected function authorizeStationAccess(Station $station): void
    {
        if ($this->isAdmin()) {
            return;
        }

        abort_unless(
            $station->users()->where('users.id', Auth::id())->exists(),
            403
        );
    }

    protected function ensureAdmin(): void
    {
        abort_unless($this->isAdmin(), 403);
    }

    protected function isAdmin(): bool
    {
        return Auth::user()?->isAdmin() ?? false;
    }

    protected function logAdminAction(
        string $action,
        string $description,
        ?object $target = null,
        array $metadata = [],
        ?string $targetType = null,
        ?int $targetId = null,
    ): void {
        AdminAuditLog::query()->create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType ?? ($target ? $target::class : null),
            'target_id' => $targetId ?? ($target->id ?? null),
            'description' => $description,
            'metadata' => $metadata,
        ]);
    }
}
