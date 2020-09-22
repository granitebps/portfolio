<?php

namespace Tests\Feature;

use App\Experience;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class ExperienceTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_experiences()
    {
        $response = $this->json('GET', '/api/v1/experience');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_experience()
    {
        $response = $this->json('POST', '/api/v1/experience', $this->experienceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/experience', $this->experienceData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Experience Created'
        ]);
    }

    /** @test */
    public function test_update_experience()
    {
        $response = $this->json('PUT', '/api/v1/experience/99', $this->experienceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/experience/99', $this->experienceData());
        $response->assertStatus(404);

        $experience = $this->createExperience();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/experience/' . $experience->id, $this->experienceData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Experience Updated'
        ]);
    }

    /** @test */
    public function test_delete_experience()
    {
        $response = $this->json('DELETE', '/api/v1/experience/99', $this->experienceData());
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/experience/99', $this->experienceData());
        $response->assertStatus(404);

        $experience = $this->createExperience();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/experience/' . $experience->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Experience Deleted'
        ]);
    }

    public function experienceData()
    {
        return [
            'company' => 'Test Experience Company',
            'position' => 'Test Experience Position',
            'desc' => 'Test Experience Description',
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addYear(),
            'current_job' => 0
        ];
    }

    public function createExperience()
    {
        $experience = Experience::create($this->experienceData());
        return $experience;
    }
}
