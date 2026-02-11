<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function assignPermission(Request $request, Role $role)
    {
        try {
            $request->validate([
                'permission_id' => 'required|exists:permissions,id',
            ]);

            $permission = Permission::findOrFail($request->permission_id);

            if ($role->permissions()->where('permission_id', $permission->id)->exists()) {
                return $this->error('Role already has this permission', 409);
            }

            $role->permissions()->attach($permission);

            return $this->success(null, 'Permission assigned to role successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function removePermission(Request $request, Role $role)
    {
        try {
            $request->validate([
                'permission_id' => 'required|exists:permissions,id',
            ]);

            $permission = Permission::findOrFail($request->permission_id);

            if (!$role->permissions()->where('permission_id', $permission->id)->exists()) {
                return $this->error('Role does not have this permission', 404);
            }

            $role->permissions()->detach($permission);

            return $this->success(null, 'Permission removed from role successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
