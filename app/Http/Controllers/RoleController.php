<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return $this->success(Role::with('permissions')->get(), 'Roles retrieved successfully');
    }

    public function show(Role $role)
    {
        return $this->success($role->load('permissions'), 'Role retrieved successfully');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:roles,name',
                'description' => 'nullable|string',
            ]);

            $role = Role::create($data);

            return $this->success($role, 'Role created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function update(Request $request, Role $role)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string|unique:roles,name,' . $role->id,
                'description' => 'nullable|string',
            ]);

            $role->update($data);

            return $this->success($role, 'Role updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return $this->success(null, 'Role deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
