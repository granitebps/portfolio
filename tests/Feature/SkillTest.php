<?php

namespace Tests\Feature;

use App\Skill;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class SkillTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_skills()
    {
        $response = $this->json('GET', '/api/v1/skill');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_skill()
    {
        $response = $this->json('POST', '/api/v1/skill', [
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/skill', [
            'name' => 'Test Skill',
            'percentage' => 90
        ]);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Skill Created'
        ]);
    }

    /** @test */
    public function test_update_skill()
    {
        $response = $this->json('PUT', '/api/v1/skill/99', [
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/skill/99', [
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response->assertStatus(400);

        $skill = Skill::create([
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('PUT', '/api/v1/skill/' . $skill->id, [
            'name' => 'Test Skill Updated',
            'percentage' => 90
        ]);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Skill Updated'
        ]);
    }

    /** @test */
    public function test_delete_skill()
    {
        $response = $this->json('DELETE', '/api/v1/skill/99', [
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/skill/99', [
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response->assertStatus(400);

        $skill = Skill::create([
            'name' => 'Test',
            'percentage' => 90
        ]);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/skill/' . $skill->id,);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Skill Deleted'
        ]);
    }
}
