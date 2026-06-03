<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Roles
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            ['description' => 'Has full access to all features and settings.']
        );

        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Can manage most aspects of the application (Restricted).']
        );

        $userRole = Role::firstOrCreate(
            ['name' => 'User'],
            ['description' => 'Regular user with standard access.']
        );

        // 2. Create Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@email.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->roles()->syncWithoutDetaching([$superAdminRole->id]);

        // 3. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $emails = ['superadmin@email.com', 'admin@email.com'];
        $users = User::whereIn('email', $emails)->get();

        foreach ($users as $user) {
            $user->roles()->detach();
            $user->delete();
        }

        Role::whereIn('name', ['Super Admin', 'Admin', 'User'])->delete();
    }
};
