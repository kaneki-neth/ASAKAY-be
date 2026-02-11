<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_permissions()
    {
        $user = User::factory()->create();
        Permission::create(['name' => 'view_dashboard', 'description' => 'Can view dashboard']);
        Permission::create(['name' => 'edit_profile', 'description' => 'Can edit profile']);

        $response = $this->actingAs($user, 'api')->getJson('/api/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'created_at',
                        'updated_at',
                    ]
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Permissions retrieved successfully'
            ]);
    }

    public function test_can_create_permission()
    {
        $user = User::factory()->create();
        $permissionData = [
            'name' => 'create_posts',
            'description' => 'Can create new posts'
        ];

        $response = $this->actingAs($user, 'api')->postJson('/api/permissions', $permissionData);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission created successfully',
                'data' => $permissionData
            ]);
    }

    public function test_can_update_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'old_name', 'description' => 'Old description']);
        $updateData = ['name' => 'new_name', 'description' => 'New description'];

        $response = $this->actingAs($user, 'api')->putJson("/api/permissions/{$permission->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission updated successfully',
                'data' => $updateData
            ]);
    }

    public function test_can_delete_permission()
    {
        $user = User::factory()->create();
        $permission = Permission::create(['name' => 'delete_me', 'description' => 'To be deleted']);

        $response = $this->actingAs($user, 'api')->deleteJson("/api/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission deleted successfully'
            ]);
        
        $this->assertDatabaseMissing('permissions', ['id' => $permission->id]);
    }

    public function test_validation_errors()
    {
        $user = User::factory()->create();
        
        // Test required fields
        $response = $this->actingAs($user, 'api')->postJson('/api/permissions', []);
        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['name']]);

        // Test unique name
        Permission::create(['name' => 'existing_permission']);
        $response = $this->actingAs($user, 'api')->postJson('/api/permissions', ['name' => 'existing_permission']);
        $response->assertStatus(422)
            ->assertJsonStructure(['errors' => ['name']]);
    }
}
