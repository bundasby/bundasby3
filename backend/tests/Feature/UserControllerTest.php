<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $regularAdmin;
    private string $superAdminToken;
    private string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $superAdminRole = Role::create([
            'name' => 'Super Admin',
            'code' => 'super_admin',
            'permissions' => json_encode(['*']),
        ]);

        $adminRole = Role::create([
            'name' => 'Admin',
            'code' => 'admin',
            'permissions' => json_encode(['manage_articles']),
        ]);

        // Create users
        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $superAdminRole->id,
            'is_active' => true,
        ]);

        $this->regularAdmin = User::create([
            'name' => 'Regular Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        $this->superAdminToken = $this->superAdmin->createToken('test')->plainTextToken;
        $this->adminToken = $this->regularAdmin->createToken('test')->plainTextToken;
    }

    public function test_super_admin_can_list_users(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->superAdminToken,
        ])->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_super_admin_can_create_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->superAdminToken,
        ])->postJson('/api/v1/users', [
                    'name' => 'New User',
                    'email' => 'newuser@test.com',
                    'password' => 'password123',
                    'password_confirmation' => 'password123',
                    'role_id' => $this->regularAdmin->role_id,
                ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'newuser@test.com']);
    }

    public function test_super_admin_can_toggle_user_active(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->superAdminToken,
        ])->patchJson("/api/v1/users/{$this->regularAdmin->id}/toggle-active");

        $response->assertStatus(200);

        $this->regularAdmin->refresh();
        $this->assertFalse($this->regularAdmin->is_active);
    }

    public function test_regular_admin_cannot_manage_users(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_delete_user(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->superAdminToken,
        ])->deleteJson("/api/v1/users/{$this->regularAdmin->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $this->regularAdmin->id]);
    }

    public function test_can_get_roles_list(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->adminToken,
        ])->getJson('/api/v1/roles');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
