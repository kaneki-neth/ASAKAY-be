<?php

namespace App\Policies;

use App\Models\Stop;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StopPolicy
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
        return $user->hasPermissionTo('stop.view');
    }

    public function view(User $user, Stop $stop): bool
    {
        return $user->hasPermissionTo('stop.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('stop.create');
    }

    public function update(User $user, Stop $stop): bool
    {
        return $user->hasPermissionTo('stop.update');
    }

    public function delete(User $user, Stop $stop): bool
    {
        return $user->hasPermissionTo('stop.delete');
    }
}
