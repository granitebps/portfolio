<?php

namespace Tests\Feature;

use App\Certification;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class CertificationTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_certifications()
    {
        $response = $this->json('GET', '/api/v1/certification');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_certification()
    {
        $response = $this->json('POST', '/api/v1/certification', $this->certificationData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/certification', $this->certificationData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Certification Created'
        ]);
    }

    /** @test */
    public function test_update_certification()
    {
        $response = $this->json('PUT', '/api/v1/certification/99', $this->certificationData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/certification/99', $this->certificationData());
        $response->assertStatus(404);

        $certification = $this->createCertification();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/certification/' . $certification->id, $this->certificationData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Certification Updated'
        ]);
    }

    /** @test */
    public function test_delete_certification()
    {
        $response = $this->json('DELETE', '/api/v1/certification/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/certification/99');
        $response->assertStatus(404);

        $certification = $this->createCertification();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/certification/' . $certification->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Certification Deleted'
        ]);
    }

    public function certificationData()
    {
        return [
            'name' => 'Test Certification',
            'institution' => 'Test Institution',
            'link' => 'https://granitebps.com',
            'published' => '2019-05-28T17:00:00.000Z',
        ];
    }

    public function createCertification()
    {
        $certification = Certification::create($this->certificationData());
        return $certification;
    }
}
