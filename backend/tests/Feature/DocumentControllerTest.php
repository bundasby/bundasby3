<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
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
            'permissions' => json_encode(['manage_documents']),
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

    public function test_can_fetch_documents(): void
    {
        Document::create([
            'title' => 'Test Document',
            'file_url' => '/docs/test.pdf',
            'category' => 'peraturan',
            'description' => 'Test description',
        ]);

        $response = $this->getJson('/api/v1/documents');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_admin_can_create_document(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/documents', [
                    'title' => 'New Document',
                    'file_url' => '/docs/new.pdf',
                    'category' => 'panduan',
                    'description' => 'New doc description',
                ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('documents', ['title' => 'New Document']);
    }

    public function test_admin_can_update_document(): void
    {
        $document = Document::create([
            'title' => 'Original',
            'file_url' => '/docs/orig.pdf',
            'category' => 'peraturan',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/documents/{$document->id}", [
                    'title' => 'Updated Title',
                ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('documents', ['title' => 'Updated Title']);
    }

    public function test_admin_can_delete_document(): void
    {
        $document = Document::create([
            'title' => 'To Delete',
            'file_url' => '/docs/delete.pdf',
            'category' => 'peraturan',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/documents/{$document->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
    }

    public function test_document_creation_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/documents', [
            'title' => 'Unauthorized',
            'file_url' => '/docs/unauth.pdf',
            'category' => 'peraturan',
        ]);

        $response->assertStatus(401);
    }

    public function test_document_validation_errors(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/documents', [
                    'title' => '',
                ]);

        $response->assertStatus(422);
    }
}
