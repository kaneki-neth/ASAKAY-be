<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleAssignmentController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::apiResource('roles', RoleController::class);

    // Role Assignment Routes
    Route::post('/users/{user}/roles', [RoleAssignmentController::class, 'assignRole']);
    Route::delete('/users/{user}/roles/{role}', [RoleAssignmentController::class, 'removeRole']);
});
