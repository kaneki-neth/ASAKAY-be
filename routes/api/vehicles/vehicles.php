<?php

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::get('vehicle-types/options', [VehicleTypeController::class, 'options']);
    Route::apiResource('vehicle-types', VehicleTypeController::class);
    Route::apiResource('vehicles', VehicleController::class);
});
