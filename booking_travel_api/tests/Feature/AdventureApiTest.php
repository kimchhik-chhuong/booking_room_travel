<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Adventure;
use App\Models\Province;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AdventureApiTest extends TestCase
{
    use RefreshDatabase;

    protected $province;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->province = Province::factory()->create();
    }

    /** @test */
    public function it_can_create_adventure_with_image()
    {
        $file = UploadedFile::fake()->image('adventure.jpg');

        $response = $this->postJson('/api/adventures', [
            'name' => 'Test Adventure',
            'description' => 'Test Description',
            'province_id' => $this->province->id,
            'image' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['status', 'message', 'data' => ['id', 'name', 'description', 'province_id', 'image']]);

        $this->assertTrue(Storage::disk('public')->exists($response->json('data.image')));
    }

    /** @test */
    public function it_validates_image_file_type()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson('/api/adventures', [
            'name' => 'Test Adventure',
            'province_id' => $this->province->id,
            'image' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function it_can_update_adventure_with_image()
    {
        $adventure = Adventure::factory()->create(['province_id' => $this->province->id]);

        $file = UploadedFile::fake()->image('new_adventure.jpg');

        $response = $this->postJson("/api/adventures/{$adventure->id}", [
            '_method' => 'PUT',
            'name' => 'Updated Adventure',
            'image' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Adventure']);

        $this->assertTrue(Storage::disk('public')->exists($response->json('data.image')));
    }
}
