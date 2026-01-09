<?php

namespace Tests\Feature;

use App\Models\Slider;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SliderControllerTest extends TestCase
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
            'permissions' => json_encode(['manage_sliders']),
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

    public function test_can_fetch_active_sliders(): void
    {
        Slider::create([
            'title' => 'Active Slide',
            'image' => '/images/slide1.jpg',
            'is_active' => true,
            'order' => 1,
        ]);

        Slider::create([
            'title' => 'Inactive Slide',
            'image' => '/images/slide2.jpg',
            'is_active' => false,
            'order' => 2,
        ]);

        $response = $this->getJson('/api/v1/sliders');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    public function test_can_fetch_all_sliders(): void
    {
        Slider::create([
            'title' => 'Slide 1',
            'image' => '/images/slide1.jpg',
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/sliders/all');

        $response->assertStatus(200);
    }

    public function test_can_create_slider(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/v1/sliders', [
                    'title' => 'New Slider',
                    'image' => '/images/new.jpg',
                    'description' => 'Test description',
                    'is_active' => true,
                ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('sliders', ['title' => 'New Slider']);
    }

    public function test_can_update_slider(): void
    {
        $slider = Slider::create([
            'title' => 'Original',
            'image' => '/images/orig.jpg',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/v1/sliders/{$slider->id}", [
                    'title' => 'Updated Title',
                ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('sliders', ['title' => 'Updated Title']);
    }

    public function test_can_toggle_slider_active(): void
    {
        $slider = Slider::create([
            'title' => 'Toggle Test',
            'image' => '/images/toggle.jpg',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->patchJson("/api/v1/sliders/{$slider->id}/toggle");

        $response->assertStatus(200);

        $slider->refresh();
        $this->assertFalse($slider->is_active);
    }

    public function test_can_delete_slider(): void
    {
        $slider = Slider::create([
            'title' => 'To Delete',
            'image' => '/images/delete.jpg',
            'is_active' => true,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/v1/sliders/{$slider->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('sliders', ['id' => $slider->id]);
    }
}
