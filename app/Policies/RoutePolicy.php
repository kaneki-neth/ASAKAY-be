<?php

namespace App\Policies;

use App\Models\Route;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoutePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->roles()->where('name', 'Super Admin')->exists()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('route.view');
    }

    public function view(User $user, Route $route): bool
    {
        return $user->hasPermissionTo('route.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('route.create');
    }

    public function update(User $user, Route $route): bool
    {
        return $user->hasPermissionTo('route.update');
    }

    public function delete(User $user, Route $route): bool
    {
        return $user->hasPermissionTo('route.delete');
    }
}
