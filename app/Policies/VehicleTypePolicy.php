<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VehicleType;
use Illuminate\Auth\Access\Response;

class VehicleTypePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->roles()->where('name', 'Super Admin')->exists()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('vehicle-type.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleType $vehicleType): bool
    {
        return $user->hasPermissionTo('vehicle-type.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('vehicle-type.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleType $vehicleType): bool
    {
        return $user->hasPermissionTo('vehicle-type.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleType $vehicleType): bool
    {
        return $user->hasPermissionTo('vehicle-type.delete');
    }
}
