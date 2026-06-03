<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\Response;

class VehiclePolicy
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
        return $user->hasPermissionTo('can_view_vehicles');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Vehicle $vehicle): bool
    {
        return $user->hasPermissionTo('can_view_vehicles');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('can_create_vehicles');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Vehicle $vehicle): bool
    {
        // Update only their own vehicles unless they have the explicit 'can_edit_vehicles' permission
        if ($user->hasPermissionTo('can_edit_vehicles')) {
            return true;
        }

        return $user->id === $vehicle->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Vehicle $vehicle): bool
    {
        // Delete only their own vehicles unless they have the explicit 'can_delete_vehicles' permission
        if ($user->hasPermissionTo('can_delete_vehicles')) {
            return true;
        }

        return $user->id === $vehicle->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Vehicle $vehicle): bool
    {
        return $user->hasPermissionTo('can_edit_vehicles');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Vehicle $vehicle): bool
    {
        return $user->hasPermissionTo('can_delete_vehicles');
    }
}
