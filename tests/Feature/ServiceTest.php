<?php

namespace Tests\Feature;

use App\Services;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class ServiceTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_services()
    {
        $response = $this->json('GET', '/api/v1/service');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_service()
    {
        $response = $this->json('POST', '/api/v1/service', $this->serviceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/service', $this->serviceData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Service Created'
        ]);
    }

    /** @test */
    public function test_update_service()
    {
        $response = $this->json('PUT', '/api/v1/service/99', $this->serviceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/service/99', $this->serviceData());
        $response->assertStatus(404);

        $service = $this->createService();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/service/' . $service->id, $this->serviceData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Service Updated'
        ]);
    }

    /** @test */
    public function test_delete_service()
    {
        $response = $this->json('DELETE', '/api/v1/service/99', $this->serviceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/service/99', $this->serviceData());
        $response->assertStatus(404);

        $service = $this->createService();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/service/' . $service->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Service Deleted'
        ]);
    }

    public function serviceData()
    {
        return [
            'name' => 'Test Service',
            'icon' => 'test-icon',
            'desc' => 'Test Description'
        ];
    }

    public function createService()
    {
        $service = Services::create($this->serviceData());
        return $service;
    }
}
