<?php

use App\Http\Controllers\Admin\StationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/stations/feed', [HomeController::class, 'feed'])->name('stations.feed');
Route::get('/stations/json', [HomeController::class, 'json'])->name('stations.json');
Route::post('/stations/{station}/crowd-feedback', [HomeController::class, 'storeCrowdFeedback'])
    ->middleware('throttle:20,1')
    ->name('stations.crowd-feedback');

Route::get('/dashboard', [StationController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::redirect('/admin', '/admin/stations');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/crowd-reports', [StationController::class, 'crowdReports'])->name('crowd-reports.index');
        Route::delete('/crowd-reports/{crowdReport}', [StationController::class, 'destroyCrowdReport'])->name('crowd-reports.destroy');
        Route::get('/audit-logs', [StationController::class, 'auditLogs'])->name('audit-logs.index');
        Route::get('/reports/stations.csv', [StationController::class, 'exportStations'])->name('reports.stations.export');
        Route::patch('/stations/{station}/status', [StationController::class, 'updateStatus'])
            ->name('stations.status');
        Route::resource('stations', StationController::class);
        Route::resource('users', UserController::class)->except('show');
    });
});

require __DIR__.'/auth.php';
