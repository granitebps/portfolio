<?php

namespace Tests\Feature;

use App\Technology;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class TechnologyTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_technology()
    {
        $response = $this->json('GET', '/api/v1/technology');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_technology()
    {
        Storage::fake('public');

        $response = $this->json('POST', '/api/v1/technology', $this->technologyData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/technology', $this->technologyData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Technology Created'
        ]);
    }

    /** @test */
    public function test_update_technology()
    {
        $response = $this->json('PUT', '/api/v1/technology/99', $this->technologyData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/technology/99', $this->technologyData());
        $response->assertStatus(404);

        $technology = $this->createTechnology();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/technology/' . $technology->id, $this->technologyData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Technology Updated'
        ]);
    }

    /** @test */
    public function test_delete_technology()
    {
        $response = $this->json('DELETE', '/api/v1/technology/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/technology/99');
        $response->assertStatus(404);

        $technology = $this->createTechnology();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/technology/' . $technology->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Technology Deleted'
        ]);
    }

    public function technologyData()
    {
        $file = UploadedFile::fake()->image('tech.jpg')->size(512);

        return [
            'name' => 'Test Technology Test',
            'pic' => $file,
        ];
    }

    public function createTechnology()
    {
        Storage::fake('public');

        $technology = Technology::create($this->technologyData());
        return $technology;
    }
}
