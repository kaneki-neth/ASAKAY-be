<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Route;
use App\Models\Stop;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function summary()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $totalUsers = User::count();
        $activeUsers = User::where('last_login_at', '>=', $thirtyDaysAgo)->count();
        
        $totalRoutes = Route::count();
        $totalStops = Stop::count();
        $totalVehicles = Vehicle::count();
        
        // Count contributors (users who have created at least one route or stop)
        $totalContributors = User::whereHas('routes')
            ->orWhereHas('stops')
            ->count();

        // If no routes/stops yet, contributors = users with 'Editor' or 'Admin' role
        if ($totalContributors === 0) {
            $totalContributors = User::whereHas('roles', function($q) {
                $q->whereIn('name', ['Super Admin', 'System Admin', 'Route Editor']);
            })->count();
        }

        return response()->json([
            'summary' => [
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'totalRoutes' => $totalRoutes,
                'totalStops' => $totalStops,
                'totalVehicles' => $totalVehicles,
                'totalContributors' => $totalContributors,
            ]
        ]);
    }

    public function recentRouteActivity(Request $request)
    {
        $limit = $request->input('limit', 5);
        $limit = min($limit, 10);

        $routes = Route::with(['vehicleType', 'creator'])
            ->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->id,
                    'name' => $route->name,
                    'transport' => $route->vehicleType->name ?? 'N/A',
                    'contributor' => $route->creator->name ?? 'System',
                    'status' => $route->status,
                    'updated_at' => $route->updated_at->toIso8601String(),
                ];
            });

        return response()->json([
            'data' => $routes
        ]);
    }

    public function recentLogins(Request $request)
    {
        $limit = $request->input('limit', 5);
        $limit = min($limit, 10); // Enforce max limit of 10

        $users = User::whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->take($limit)
            ->with('roles')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->pluck('name')->first() ?? 'N/A', // Assuming single primary role or taking the first one
                    'lastLoginAt' => $user->last_login_at->toIso8601String(),
                ];
            });

        return response()->json([
            'data' => $users
        ]);
    }
}
