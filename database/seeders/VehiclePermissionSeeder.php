<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class VehiclePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'can_view_vehicles',
                'description' => 'Can view vehicles list and details',
            ],
            [
                'name' => 'can_create_vehicles',
                'description' => 'Can create new vehicles',
            ],
            [
                'name' => 'can_edit_vehicles',
                'description' => 'Can edit existing vehicles',
            ],
            [
                'name' => 'can_delete_vehicles',
                'description' => 'Can delete vehicles',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // Assign all to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $allPermissions = Permission::where('name', 'like', 'can_%_vehicles')->get();
            $superAdmin->permissions()->syncWithoutDetaching($allPermissions);
        }

        // Assign all to Admin as well
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $allPermissions = Permission::where('name', 'like', 'can_%_vehicles')->get();
            $admin->permissions()->syncWithoutDetaching($allPermissions);
        }
    }
}
