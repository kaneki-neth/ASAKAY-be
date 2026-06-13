<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckRole;

Route::middleware(['auth:api', CheckRole::class . ':Super Admin,System Admin'])->prefix('admin/dashboard')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'summary']);
    Route::get('/recent-logins', [AdminDashboardController::class, 'recentLogins']);
    Route::get('/recent-route-activity', [AdminDashboardController::class, 'recentRouteActivity']);
});
