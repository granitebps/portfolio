<?php

namespace Tests\Feature;

use App\Models\Gallery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class GalleryTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_galleries()
    {
        $response = $this->json('GET', '/api/v1/gallery');
        $response->assertStatus(401);

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/gallery');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_update_gallery()
    {
        Storage::fake('gallery');

        $response = $this->json('POST', '/api/v1/gallery', $this->galleryData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/gallery', $this->galleryData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'File Uploaded'
        ]);
    }

    /** @test */
    public function test_delete_gallery()
    {
        $response = $this->json('DELETE', '/api/v1/gallery/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/gallery/99');
        $response->assertStatus(404);

        $gallery = $this->createGallery();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/gallery/' . $gallery->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'File Deleted'
        ]);
    }

    public function galleryData()
    {
        $file = UploadedFile::fake()->image('gallery.jpg')->size(512);

        return [
            'file' => $file
        ];
    }

    public function createGallery()
    {
        Storage::fake('gallery');

        $file = UploadedFile::fake()->image('gallery.jpg');

        $gallery = Gallery::create([
            'name' => $file,
        ]);
        return $gallery;
    }
}
