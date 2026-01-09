<?php

namespace Tests\Feature;

use App\Models\BundaPaud;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BundaPaudControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Admin',
            'code' => 'admin',
            'permissions' => json_encode(['manage_bunda_paud']),
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->token = $this->adminUser->createToken('test-token')->plainTextToken;
    }

    public function test_can_fetch_bunda_paud_list(): void
    {
        BundaPaud::create([
            'name' => 'Test Bunda',
            'position' => 'Ketua',
            'kecamatan' => 'Gubeng',
            'kelurahan' => 'Airlangga',
            'phone' => '081234567890',
        ]);

        $response = $this->getJson('/api/v1/bunda-paud');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_get_kecamatan_list(): void
    {
        BundaPaud::create([
            'name' => 'Bunda 1',
            'position' => 'Ketua',
            'kecamatan' => 'Gubeng',
        ]);

        BundaPaud::create([
            'name' => 'Bunda 2',
            'position' => 'Ketua',
            'kecamatan' => 'Mulyorejo',
        ]);

        $response = $this->getJson('/api/v1/bunda-paud/kecamatan');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_create_bunda_paud(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/bunda-paud', [
                    'name' => 'New Bunda',
                    'position' => 'Wakil Ketua',
                    'kecamatan' => 'Sukolilo',
                    'kelurahan' => 'Keputih',
                    'phone' => '082345678901',
                ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bunda_paud', ['name' => 'New Bunda']);
    }

    public function test_admin_can_update_bunda_paud(): void
    {
        $bundaPaud = BundaPaud::create([
            'name' => 'Original Name',
            'position' => 'Ketua',
            'kecamatan' => 'Gubeng',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/bunda-paud/{$bundaPaud->id}", [
                    'name' => 'Updated Name',
                ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bunda_paud', ['name' => 'Updated Name']);
    }

    public function test_admin_can_delete_bunda_paud(): void
    {
        $bundaPaud = BundaPaud::create([
            'name' => 'To Delete',
            'position' => 'Ketua',
            'kecamatan' => 'Gubeng',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/bunda-paud/{$bundaPaud->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('bunda_paud', ['id' => $bundaPaud->id]);
    }

    public function test_bunda_paud_creation_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/bunda-paud', [
            'name' => 'Unauthorized',
            'position' => 'Ketua',
            'kecamatan' => 'Gubeng',
        ]);

        $response->assertStatus(401);
    }
}
