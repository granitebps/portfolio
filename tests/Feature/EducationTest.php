<?php

namespace Tests\Feature;

use App\Models\Education;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class EducationTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_educations()
    {
        $response = $this->json('GET', '/api/v1/education');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_education()
    {
        $response = $this->json('POST', '/api/v1/education', $this->educationData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/education', $this->educationData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Education Created'
        ]);
    }

    /** @test */
    public function test_update_education()
    {
        $response = $this->json('PUT', '/api/v1/education/99', $this->educationData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/education/99', $this->educationData());
        $response->assertStatus(404);

        $education = $this->createEducation();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/education/' . $education->id, $this->educationData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Education Updated'
        ]);
    }

    /** @test */
    public function test_delete_education()
    {
        $response = $this->json('DELETE', '/api/v1/education/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/education/99');
        $response->assertStatus(404);

        $education = $this->createEducation();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/education/' . $education->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Education Deleted'
        ]);
    }

    public function educationData()
    {
        return [
            'name' => 'Test Education Name',
            'institute' => 'Test Education Institute',
            'start_year' => '2000',
            'end_year' => '2020'
        ];
    }

    public function createEducation()
    {
        $education = Education::create($this->educationData());
        return $education;
    }
}
