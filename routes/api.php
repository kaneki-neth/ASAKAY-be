<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

include __DIR__ . '/api/users/users.php';
include __DIR__ . '/api/roles/roles.php';
include __DIR__ . '/api/permissions/permissions.php';
include __DIR__ . '/api/vehicles/vehicles.php';
include __DIR__ . '/api/admin/dashboard.php';
