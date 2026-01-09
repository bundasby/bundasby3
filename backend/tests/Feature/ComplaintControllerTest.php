<?php

namespace Tests\Feature;

use App\Models\Complaint;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComplaintControllerTest extends TestCase
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
            'permissions' => json_encode(['manage_complaints']),
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

    public function test_can_submit_complaint_publicly(): void
    {
        $response = $this->postJson('/api/v1/complaints', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '081234567890',
            'subject' => 'Test Complaint',
            'message' => 'This is a test complaint message',
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('complaints', ['email' => 'john@example.com']);
    }

    public function test_complaint_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/complaints', [
            'name' => '',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422);
    }

    public function test_admin_can_view_complaints(): void
    {
        Complaint::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'status' => 'pending',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v1/complaints');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_update_complaint_status(): void
    {
        $complaint = Complaint::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'status' => 'pending',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/complaints/{$complaint->id}", [
                    'status' => 'resolved',
                    'response' => 'Issue has been resolved',
                ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('complaints', [
            'id' => $complaint->id,
            'status' => 'resolved',
        ]);
    }

    public function test_complaint_list_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/complaints');
        $response->assertStatus(401);
    }

    public function test_can_view_single_complaint(): void
    {
        $complaint = Complaint::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'subject' => 'Test Subject',
            'message' => 'Test Message',
            'status' => 'pending',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/v1/complaints/{$complaint->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.subject', 'Test Subject');
    }
}
