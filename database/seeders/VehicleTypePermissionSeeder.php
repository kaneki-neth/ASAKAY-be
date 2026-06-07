<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class VehicleTypePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'vehicle-type.view', 'description' => 'View vehicle types'],
            ['name' => 'vehicle-type.create', 'description' => 'Create vehicle types'],
            ['name' => 'vehicle-type.update', 'description' => 'Update vehicle types'],
            ['name' => 'vehicle-type.delete', 'description' => 'Delete vehicle types'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Assign to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->permissions()->syncWithoutDetaching(Permission::whereIn('name', array_column($permissions, 'name'))->pluck('id'));
        }

        // Assign to Admin
        $admin = Role::where('name', 'Admin')->first();
        if ($admin) {
            $admin->permissions()->syncWithoutDetaching(Permission::whereIn('name', array_column($permissions, 'name'))->pluck('id'));
        }
    }
}
