<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class AuthTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    public function test_success_login()
    {
        $this->authenticate();

        $response = $this->json('POST', '/api/v1/auth/login', [
            'username' => 'admin',
            'password' => '12345678'
        ]);

        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => '',
        ]);
    }

    public function test_failed_login()
    {
        $this->authenticate();

        $response = $this->json('POST', '/api/v1/auth/login', [
            'username' => 'granitebps',
            'password' => 'passwordsalah'
        ]);

        $response->assertStatus(401)->assertExactJson([
            'success' => false,
            'message' => 'Username or Password Is Wrong',
            'data' => []
        ]);
    }

    public function test_authenticated()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/auth/me');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    public function test_unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/auth/me');
        $response->assertStatus(401)->assertJson([
            'message' => 'Unauthenticated',
        ]);
    }
}
