<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Super Admin',
                'description' => 'Has full access to all features and settings.',
            ],
            [
                'name' => 'Admin',
                'description' => 'Can manage most aspects of the application but has some restrictions.',
            ],
            [
                'name' => 'User',
                'description' => 'Regular user with standard access.',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}
