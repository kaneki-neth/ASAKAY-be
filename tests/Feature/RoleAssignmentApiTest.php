<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAssignmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_assign_role_to_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $response = $this->actingAs($admin, 'api')->postJson("/api/users/{$user->id}/roles", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role assigned successfully'
            ]);

        $this->assertTrue($user->roles()->where('role_id', $role->id)->exists());
    }

    public function test_cannot_assign_same_role_twice()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $response = $this->actingAs($admin, 'api')->postJson("/api/users/{$user->id}/roles", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(409)
            ->assertJson([
                'status' => 'error',
                'message' => 'User already has this role'
            ]);
    }

    public function test_can_remove_role_from_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $response = $this->actingAs($admin, 'api')->deleteJson("/api/users/{$user->id}/roles", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Role removed successfully'
            ]);

        $this->assertFalse($user->roles()->where('role_id', $role->id)->exists());
    }

    public function test_cannot_remove_unassigned_role()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $response = $this->actingAs($admin, 'api')->deleteJson("/api/users/{$user->id}/roles", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'error',
                'message' => 'User does not have this role'
            ]);
    }
}
