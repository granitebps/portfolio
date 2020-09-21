<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class SkillTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    public function test_get_skills()
    {
        $response = $this->json('GET', '/api/v1/skill');

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    public function test_create_skills()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('POST', '/api/v1/skill', [
            'name' => 'Test',
            'percentage' => 90
        ]);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Skill Created'
        ]);
    }
}
