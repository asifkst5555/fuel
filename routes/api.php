<?php

use App\Http\Controllers\Api\StationStatusController;
use Illuminate\Support\Facades\Route;

Route::get('/stations', [StationStatusController::class, 'index']);
