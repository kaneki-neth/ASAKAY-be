<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        // Create Super Admin
        $superAdmin = \App\Models\User::updateOrCreate(
            ['email' => 'superadmin@email.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
            ]
        );
        $superAdmin->roles()->syncWithoutDetaching([
            \App\Models\Role::where('name', 'Super Admin')->first()->id
        ]);

        // Create Admin
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->roles()->syncWithoutDetaching([
            \App\Models\Role::where('name', 'Admin')->first()->id
        ]);

        // Create Regular User
        $user = \App\Models\User::updateOrCreate(
            ['email' => 'test@email.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $user->roles()->syncWithoutDetaching([
            \App\Models\Role::where('name', 'User')->first()->id
        ]);
        $this->call([
            PermissionSeeder::class,
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
