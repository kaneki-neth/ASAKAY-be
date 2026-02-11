<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleAssignmentController extends Controller
{
    public function assignRole(Request $request, User $user)
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            $role = Role::findOrFail($request->role_id);

            // Check if user already has the role
            if ($user->roles()->where('role_id', $role->id)->exists()) {
                return $this->error('User already has this role', 409);
            }

            $user->roles()->attach($role);

            return $this->success(null, 'Role assigned successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function removeRole(User $user, Role $role)
    {
        try {
            if (!$user->roles()->where('role_id', $role->id)->exists()) {
                return $this->error('User does not have this role', 404);
            }

            $user->roles()->detach($role->id);

            return $this->success(null, 'Role removed successfully');
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
