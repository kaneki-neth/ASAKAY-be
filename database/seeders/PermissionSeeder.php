<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'can_view_users',
                'description' => 'Can view users list and details',
            ],
            [
                'name' => 'can_create_users',
                'description' => 'Can create new users',
            ],
            [
                'name' => 'can_edit_users',
                'description' => 'Can edit existing users',
            ],
            [
                'name' => 'can_delete_users',
                'description' => 'Can delete users',
            ],
            [
                'name' => 'can_view_roles',
                'description' => 'Can view roles list and details',
            ],
            [
                'name' => 'can_create_roles',
                'description' => 'Can create new roles',
            ],
            [
                'name' => 'can_edit_roles',
                'description' => 'Can edit existing roles',
            ],
            [
                'name' => 'can_delete_roles',
                'description' => 'Can delete roles',
            ],
            [
                'name' => 'can_view_permissions',
                'description' => 'Can view permissions list and details',
            ],
            [
                'name' => 'can_create_permissions',
                'description' => 'Can create new permissions',
            ],
            [
                'name' => 'can_edit_permissions',
                'description' => 'Can edit existing permissions',
            ],
            [
                'name' => 'can_delete_permissions',
                'description' => 'Can delete permissions',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Assign all permissions to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::all();
            $superAdmin->permissions()->sync($allPermissions);
        }

        // Assign view permissions to Admin
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $viewPermissions = Permission::where('name', 'like', 'view_%')->get();
            $admin->permissions()->syncWithoutDetaching($viewPermissions);
        }
    }
}
