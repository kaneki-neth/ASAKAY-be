<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_roles()
    {
        $user = User::factory()->create();
        Role::factory(3)->create();

        $response = $this->actingAs($user, 'api')->getJson('/api/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'permissions'
                    ]
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Roles retrieved successfully'
            ]);
    }

    public function test_can_show_role_with_permissions()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        
        $response = $this->actingAs($user, 'api')->getJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'description',
                    'permissions'
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Role retrieved successfully'
            ]);
    }

    public function test_can_create_role()
    {
        $user = User::factory()->create();
        $roleData = [
            'name' => 'Admin',
            'description' => 'Administrator role'
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/roles', $roleData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role created successfully',
                'data' => [
                    'name' => 'Admin',
                    'description' => 'Administrator role'
                ]
            ]);
    }

    public function test_validation_error_on_create_role()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user, 'api')->postJson('/api/roles', []);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Validation failed'
            ]);
    }

    public function test_can_update_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $updateData = ['name' => 'Updated Role'];

        $response = $this->actingAs($user, 'api')->putJson("/api/roles/{$role->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role updated successfully',
                'data' => [
                    'id' => $role->id,
                    'name' => 'Updated Role'
                ]
            ]);
    }

    public function test_can_delete_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $response = $this->actingAs($user, 'api')->deleteJson("/api/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role deleted successfully'
            ]);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }
}
