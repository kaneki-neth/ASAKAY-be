<?php

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\NavigationController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('vehicle-types/options', [VehicleTypeController::class, 'options']);
    Route::apiResource('vehicle-types', VehicleTypeController::class);
    Route::apiResource('vehicles', VehicleController::class);

    Route::get('stops/all', [StopController::class, 'all']);
    Route::apiResource('stops', StopController::class);
    Route::apiResource('routes', RouteController::class);

    Route::post('navigate', [NavigationController::class, 'navigate']);
});
