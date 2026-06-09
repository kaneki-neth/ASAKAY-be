<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RouteStopPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'route.view', 'description' => 'View routes'],
            ['name' => 'route.create', 'description' => 'Create routes'],
            ['name' => 'route.update', 'description' => 'Update routes'],
            ['name' => 'route.delete', 'description' => 'Delete routes'],
            ['name' => 'stop.view', 'description' => 'View stops'],
            ['name' => 'stop.create', 'description' => 'Create stops'],
            ['name' => 'stop.update', 'description' => 'Update stops'],
            ['name' => 'stop.delete', 'description' => 'Delete stops'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Assign to Super Admin and Admin
        $roles = Role::whereIn('name', ['Super Admin', 'Admin'])->get();
        $allPermissions = Permission::whereIn('name', array_column($permissions, 'name'))->pluck('id');

        foreach ($roles as $role) {
            $role->permissions()->syncWithoutDetaching($allPermissions);
        }
    }
}
