<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        return $this->success(Permission::all(), 'Permissions retrieved successfully');
    }

    public function show(Permission $permission)
    {
        return $this->success($permission, 'Permission retrieved successfully');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|unique:permissions,name',
                'description' => 'nullable|string',
            ]);

            $permission = Permission::create($data);

            return $this->success($permission, 'Permission created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function update(Request $request, Permission $permission)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string|unique:permissions,name,' . $permission->id,
                'description' => 'nullable|string',
            ]);

            $permission->update($data);

            return $this->success($permission, 'Permission updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }

    public function destroy(Permission $permission)
    {
        try {
            $permission->delete();
            return $this->success(null, 'Permission deleted successfully');
        } catch (\Exception $e) {
            return $this->error('Something went wrong', 500, $e->getMessage());
        }
    }
}
