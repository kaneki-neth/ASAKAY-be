<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(30)->create()->each(function ($user) {
            $user->roles()->syncWithoutDetaching([
                \App\Models\Role::where('name', 'User')->first()->id
            ]);
        });
    }
}
