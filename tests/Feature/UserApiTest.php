<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_users()
    {
        $user = User::factory()->create();
        User::factory(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'roles'
                    ]
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Users retrieved successfully'
            ]);
    }

    public function test_can_show_user_with_roles()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'roles'
                ]
            ]);
    }

    public function test_can_create_user()
    {
        $user = User::factory()->create();
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => [
                    'name' => 'Test User',
                    'email' => 'test@example.com'
                ]
            ]);
    }

    public function test_validation_error_on_create()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->postJson('/api/users', []);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Validation failed'
            ])
            ->assertJsonStructure([
                'errors' => ['name', 'email', 'password']
            ]);
    }

    public function test_can_update_user()
    {
        $user = User::factory()->create();
        $updateData = ['name' => 'Updated Name'];

        $response = $this->actingAs($user, 'api')->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => 'Updated Name'
                ]
            ]);
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/users/{$otherUser->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);

        $this->assertDatabaseMissing('users', ['id' => $otherUser->id]);
    }
}
