<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCrowdFeedbackRequest;
use App\Models\Station;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        return view('home.index', [
            'stations' => $this->stationsQuery($request)->get(),
            'search' => (string) $request->string('search'),
            'availability' => (string) $request->string('availability'),
            'crowd' => (string) $request->string('crowd'),
            'sort' => (string) $request->string('sort'),
        ]);
    }

    public function feed(Request $request): View
    {
        return view('home.partials.station-cards', [
            'stations' => $this->stationsQuery($request)->get(),
        ]);
    }

    public function json(Request $request): JsonResponse
    {
        $stations = $this->stationsQuery($request)->get()->map(function (Station $station): array {
            $status = $station->fuelStatus;
            $crowd = $station->latestCrowdReport;

            return [
                'id' => $station->id,
                'name' => $station->name,
                'location' => $station->location,
                'dealer' => $station->dealer,
                'octane' => (bool) optional($status)->octane,
                'petrol' => (bool) optional($status)->petrol,
                'diesel' => (bool) optional($status)->diesel,
                'crowd_level' => $crowd?->crowd_level,
                'crowd_updated_human' => $crowd?->updated_at?->diffForHumans(),
                'last_updated' => optional($status?->updated_at)->toIso8601String(),
                'last_updated_human' => $status?->updated_at?->diffForHumans() ?? 'Not updated yet',
            ];
        });

        return response()->json([
            'data' => $stations,
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function storeCrowdFeedback(StoreCrowdFeedbackRequest $request, Station $station): RedirectResponse
    {
        $station->crowdReports()->create([
            'crowd_level' => $request->validated('crowd_level'),
            'ip_address' => $request->ip(),
        ]);

        return back()->with('crowd_feedback_status', '????? ??? ??????? ??????? ????? ??? ??????');
    }

    protected function stationsQuery(Request $request): Builder
    {
        $query = Station::query()
            ->with(['fuelStatus', 'latestCrowdReport'])
            ->when($request->filled('search'), function (Builder $query) use ($request): void {
                $search = (string) $request->string('search');

                $query->where(function (Builder $innerQuery) use ($search): void {
                    $innerQuery
                        ->where('name', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('dealer', 'like', '%'.$search.'%');
                });
            })
            ->when($request->string('availability')->toString() === 'octane', function (Builder $query): void {
                $query->whereHas('fuelStatus', fn (Builder $builder) => $builder->where('octane', true));
            })
            ->when($request->string('availability')->toString() === 'petrol', function (Builder $query): void {
                $query->whereHas('fuelStatus', fn (Builder $builder) => $builder->where('petrol', true));
            })
            ->when($request->string('availability')->toString() === 'diesel', function (Builder $query): void {
                $query->whereHas('fuelStatus', fn (Builder $builder) => $builder->where('diesel', true));
            })
            ->when(in_array($request->string('availability')->toString(), ['all', 'both'], true), function (Builder $query): void {
                $query->whereHas('fuelStatus', fn (Builder $builder) => $builder->where('octane', true)->where('petrol', true)->where('diesel', true));
            })
            ->when($request->filled('crowd'), function (Builder $query) use ($request): void {
                $query->whereHas('latestCrowdReport', function (Builder $builder) use ($request): void {
                    $builder->where('crowd_level', $request->string('crowd')->toString());
                });
            });

        return match ($request->string('sort')->toString()) {
            'updated' => $query->leftJoin('fuel_statuses', 'stations.id', '=', 'fuel_statuses.station_id')
                ->select('stations.*')
                ->orderByDesc('fuel_statuses.updated_at'),
            'crowd' => $query->leftJoin('crowd_reports as latest_crowd', function ($join): void {
                    $join->on('stations.id', '=', 'latest_crowd.station_id');
                })
                ->select('stations.*')
                ->orderByDesc('latest_crowd.updated_at'),
            default => $query->orderBy('name'),
        };
    }
}
