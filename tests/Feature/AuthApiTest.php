<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $role = \App\Models\Role::factory()->create(['name' => 'Test Role']);
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);
        $user->roles()->sync([$role->id]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user' => [
                        'roles' => [
                            '*' => [
                                'name',
                                'permissions'
                            ]
                        ]
                    ]
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Login successful',
            ]);
        
        $this->assertEquals('Test Role', $response->json('data.user.roles.0.name'));
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_user_can_get_own_profile()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User profile retrieved successfully',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ]
            ]);
    }

    public function test_user_can_get_own_profile_with_roles_and_permissions()
    {
        $role = \App\Models\Role::factory()->create(['name' => 'Test Role']);
        $permission = \App\Models\Permission::create([
            'name' => 'test_permission',
            'description' => 'Test Permission Description'
        ]);
        $role->permissions()->attach($permission);

        $user = User::factory()->create();
        $user->roles()->sync([$role->id]);

        $token = auth('api')->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'roles' => [
                        '*' => [
                            'name',
                            'permissions' => [
                                '*' => ['name']
                            ]
                        ]
                    ]
                ]
            ]);
        
        $this->assertEquals('Test Role', $response->json('data.roles.0.name'));
        $this->assertEquals('test_permission', $response->json('data.roles.0.permissions.0.name'));
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged out successfully',
            ]);
    }

    public function test_unauthenticated_user_cannot_access_protected_routes()
    {
        $response = $this->getJson('/api/me');

        // Note: Default Laravel auth middleware might return different structure or 401
        // We need to see if it uses our custom response or default.
        // Usually middleware returns default 401.
        $response->assertStatus(401);
    }
}
