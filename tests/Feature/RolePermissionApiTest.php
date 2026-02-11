<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_assign_permission_to_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'manage_users']);

        $response = $this->actingAs($user, 'api')->postJson("/api/roles/{$role->id}/permissions", [
            'permission_id' => $permission->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission assigned to role successfully'
            ]);

        $this->assertTrue($role->permissions()->where('permission_id', $permission->id)->exists());
    }

    public function test_can_remove_permission_from_role()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'manage_users']);
        $role->permissions()->attach($permission);

        $response = $this->actingAs($user, 'api')->deleteJson("/api/roles/{$role->id}/permissions", [
            'permission_id' => $permission->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Permission removed from role successfully'
            ]);

        $this->assertFalse($role->permissions()->where('permission_id', $permission->id)->exists());
    }

    public function test_cannot_assign_duplicate_permission()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin']);
        $permission = Permission::create(['name' => 'manage_users']);
        $role->permissions()->attach($permission);

        $response = $this->actingAs($user, 'api')->postJson("/api/roles/{$role->id}/permissions", [
            'permission_id' => $permission->id
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'status' => 'error',
                'message' => 'Role already has this permission'
            ]);
    }

    public function test_validation_errors()
    {
        $user = User::factory()->create();
        $role = Role::create(['name' => 'Admin']);

        // Test missing permission_id
        $response = $this->actingAs($user, 'api')->postJson("/api/roles/{$role->id}/permissions", []);
        $response->assertStatus(422);

        // Test invalid permission_id
        $response = $this->actingAs($user, 'api')->postJson("/api/roles/{$role->id}/permissions", [
            'permission_id' => 999
        ]);
        $response->assertStatus(422);
    }
}
