<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolePermissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('permissions', PermissionController::class);

    // Role Permission Assignment Routes
    Route::post('/roles/{role}/permissions', [RolePermissionController::class, 'assignPermission']);
    Route::delete('/roles/{role}/permissions', [RolePermissionController::class, 'removePermission']);
});
