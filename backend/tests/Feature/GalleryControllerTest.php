<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        $role = Role::create([
            'name' => 'Admin',
            'code' => 'admin',
            'permissions' => json_encode(['manage_galleries']),
        ]);

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Generate token
        $this->token = $this->adminUser->createToken('test-token')->plainTextToken;
    }

    /**
     * Test can fetch all active gallery items
     */
    public function test_can_fetch_active_galleries(): void
    {
        // Create active gallery
        Gallery::create([
            'title' => 'Active Image',
            'image_url' => '/images/active.jpg',
            'category' => 'kegiatan',
            'is_active' => true,
            'order' => 1,
        ]);

        // Create inactive gallery
        Gallery::create([
            'title' => 'Inactive Image',
            'image_url' => '/images/inactive.jpg',
            'category' => 'kegiatan',
            'is_active' => false,
            'order' => 2,
        ]);

        $response = $this->getJson('/api/v1/galleries');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(1, 'data'); // Only active
    }

    /**
     * Test galleries can be grouped by category
     */
    public function test_galleries_can_be_grouped_by_category(): void
    {
        Gallery::create([
            'title' => 'Kegiatan 1',
            'image_url' => '/images/kegiatan1.jpg',
            'category' => 'kegiatan',
            'is_active' => true,
        ]);

        Gallery::create([
            'title' => 'Edukasi 1',
            'image_url' => '/images/edukasi1.jpg',
            'category' => 'edukasi',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/galleries?grouped=true');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['kegiatan', 'edukasi']]);
    }

    /**
     * Test admin can create gallery item
     */
    public function test_admin_can_create_gallery(): void
    {
        $galleryData = [
            'title' => 'New Gallery',
            'image_url' => '/images/new.jpg',
            'category' => 'pengumuman',
            'description' => 'Test description',
            'is_active' => true,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/galleries', $galleryData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Gallery item created successfully',
            ]);

        $this->assertDatabaseHas('galleries', ['title' => 'New Gallery']);
    }

    /**
     * Test gallery creation requires authentication
     */
    public function test_gallery_creation_requires_authentication(): void
    {
        $response = $this->postJson('/api/v1/galleries', [
            'title' => 'Unauthorized Gallery',
            'image_url' => '/images/test.jpg',
            'category' => 'kegiatan',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test admin can update gallery
     */
    public function test_admin_can_update_gallery(): void
    {
        $gallery = Gallery::create([
            'title' => 'Original Title',
            'image_url' => '/images/original.jpg',
            'category' => 'kegiatan',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/galleries/{$gallery->id}", [
                    'title' => 'Updated Title',
                ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Gallery item updated successfully',
            ]);

        $this->assertDatabaseHas('galleries', ['title' => 'Updated Title']);
    }

    /**
     * Test admin can delete gallery
     */
    public function test_admin_can_delete_gallery(): void
    {
        $gallery = Gallery::create([
            'title' => 'To Delete',
            'image_url' => '/images/delete.jpg',
            'category' => 'kegiatan',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/galleries/{$gallery->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Gallery item deleted successfully',
            ]);

        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
    }

    /**
     * Test gallery validation errors
     */
    public function test_gallery_validation_errors(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/galleries', [
                    'title' => '', // Empty title
                ]);

        $response->assertStatus(422);
    }
}
