<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AdminDashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_dashboard_summary()
    {
        $role = Role::factory()->create(['name' => 'System Admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($role);

        // Create active users (logged in within last 30 days)
        User::factory(3)->create(['last_login_at' => Carbon::now()->subDays(5)]);

        // Create inactive users (logged in > 30 days ago or never)
        User::factory(2)->create(['last_login_at' => Carbon::now()->subDays(40)]);
        User::factory(1)->create(['last_login_at' => null]);

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'summary' => [
                    'totalUsers',
                    'activeUsers',
                    'inactiveUsers',
                ]
            ]);

        $totalUsers = 1 + 3 + 2 + 1; // Admin + 3 active + 2 inactive + 1 never logged in
        $activeUsers = 3; // 3 active users (Admin last_login_at is null initially unless updated)
        // Note: Admin user created above doesn't have last_login_at set in factory, so it counts as inactive unless we set it.
        // Let's verify exact counts.
        // Active: 3 users
        // Inactive: 2 (>30 days) + 1 (null) + 1 (Admin null) = 4
        // Total: 7

        $response->assertJson([
            'summary' => [
                'totalUsers' => 7,
                'activeUsers' => 3,
                'inactiveUsers' => 4,
            ]
        ]);
    }

    public function test_can_get_recent_logins()
    {
        $role = Role::factory()->create(['name' => 'System Admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($role);

        $user1 = User::factory()->create(['name' => 'User 1', 'last_login_at' => Carbon::now()->subMinutes(10)]);
        $user2 = User::factory()->create(['name' => 'User 2', 'last_login_at' => Carbon::now()->subMinutes(20)]);
        
        // User without last_login_at should not appear
        User::factory()->create(['last_login_at' => null]);

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/dashboard/recent-logins');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'lastLoginAt',
                    ]
                ]
            ]);
            
        $response->assertJsonCount(2, 'data');
        
        // Verify order (most recent first)
        $data = $response->json('data');
        $this->assertEquals($user1->id, $data[0]['id']);
        $this->assertEquals($user2->id, $data[1]['id']);
    }

    public function test_recent_logins_limit()
    {
        $role = Role::factory()->create(['name' => 'System Admin']);
        $admin = User::factory()->create();
        $admin->roles()->attach($role);

        User::factory(10)->create(['last_login_at' => Carbon::now()]);

        $response = $this->actingAs($admin, 'api')->getJson('/api/admin/dashboard/recent-logins?limit=3');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_only_system_admin_can_access_dashboard()
    {
        $role = Role::factory()->create(['name' => 'User']);
        $user = User::factory()->create();
        $user->roles()->attach($role);

        $response = $this->actingAs($user, 'api')->getJson('/api/admin/dashboard');

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->getJson('/api/admin/dashboard');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }
}
