<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin role
        $role = Role::create([
            'name' => 'Super Admin',
            'code' => 'super_admin',
            'permissions' => json_encode(['*']),
        ]);

        // Create admin user
        $this->adminUser = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        // Generate token
        $this->token = $this->adminUser->createToken('test-token')->plainTextToken;
    }

    /**
     * Test can fetch all published articles
     */
    public function test_can_fetch_published_articles(): void
    {
        // Create published article
        Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'category' => 'kegiatan',
            'author_id' => $this->adminUser->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        // Create unpublished article
        Article::create([
            'title' => 'Draft Article',
            'slug' => 'draft-article',
            'excerpt' => 'Draft excerpt',
            'content' => 'Draft content',
            'category' => 'edukasi',
            'author_id' => $this->adminUser->id,
            'is_published' => false,
        ]);

        $response = $this->getJson('/api/v1/articles');

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(1, 'data.data'); // Only published
    }

    /**
     * Test can fetch single article by slug
     */
    public function test_can_fetch_article_by_slug(): void
    {
        Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'excerpt' => 'Test excerpt',
            'content' => 'Test content',
            'category' => 'kegiatan',
            'author_id' => $this->adminUser->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/articles/test-article');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => ['title' => 'Test Article'],
            ]);
    }

    /**
     * Test admin can create article
     */
    public function test_admin_can_create_article(): void
    {
        $articleData = [
            'title' => 'New Article',
            'slug' => 'new-article',
            'excerpt' => 'New excerpt',
            'content' => 'New content here',
            'category' => 'pengumuman',
            'is_published' => true,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/articles', $articleData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Article created successfully',
            ]);

        $this->assertDatabaseHas('articles', ['slug' => 'new-article']);
    }

    /**
     * Test article creation requires authentication
     */
    public function test_article_creation_requires_authentication(): void
    {
        $articleData = [
            'title' => 'Unauthorized Article',
            'slug' => 'unauthorized-article',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'category' => 'kegiatan',
        ];

        $response = $this->postJson('/api/v1/articles', $articleData);

        $response->assertStatus(401);
    }

    /**
     * Test admin can update article
     */
    public function test_admin_can_update_article(): void
    {
        $article = Article::create([
            'title' => 'Original Title',
            'slug' => 'original-title',
            'excerpt' => 'Original excerpt',
            'content' => 'Original content',
            'category' => 'kegiatan',
            'author_id' => $this->adminUser->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/articles/{$article->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Article updated successfully',
            ]);

        $this->assertDatabaseHas('articles', ['title' => 'Updated Title']);
    }

    /**
     * Test admin can delete article
     */
    public function test_admin_can_delete_article(): void
    {
        $article = Article::create([
            'title' => 'To Delete',
            'slug' => 'to-delete',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'category' => 'kegiatan',
            'author_id' => $this->adminUser->id,
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/articles/{$article->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Article deleted successfully',
            ]);

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    /**
     * Test article validation errors
     */
    public function test_article_validation_errors(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/articles', [
            'title' => '', // Empty title
        ]);

        $response->assertStatus(422);
    }
}
