<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Illuminate\Http\JsonResponse;

class StationStatusController extends Controller
{
    public function index(): JsonResponse
    {
        $stations = Station::query()
            ->with('fuelStatus')
            ->orderBy('name')
            ->get()
            ->map(function (Station $station): array {
                $status = $station->fuelStatus;

                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'location' => $station->location,
                    'fuel_status' => [
                        'octane' => (bool) optional($status)->octane,
                        'diesel' => (bool) optional($status)->diesel,
                    ],
                    'updated_at' => optional($status?->updated_at)->toIso8601String(),
                    'updated_at_human' => $status?->updated_at?->diffForHumans() ?? 'Not updated yet',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $stations,
        ]);
    }
}
