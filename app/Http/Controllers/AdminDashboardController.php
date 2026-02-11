<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function summary()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $totalUsers = User::count();
        $activeUsers = User::where('last_login_at', '>=', $thirtyDaysAgo)->count();
        $inactiveUsers = User::where(function ($query) use ($thirtyDaysAgo) {
            $query->where('last_login_at', '<', $thirtyDaysAgo)
                  ->orWhereNull('last_login_at');
        })->count();

        return response()->json([
            'summary' => [
                'totalUsers' => $totalUsers,
                'activeUsers' => $activeUsers,
                'inactiveUsers' => $inactiveUsers,
            ]
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
