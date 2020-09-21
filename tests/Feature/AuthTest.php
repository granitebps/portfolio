<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    public function test_failed_login()
    {
        $response = $this->post('/api/v1/auth/login', [
            'username' => 'granitebps',
            'password' => 'passwordsalah'
        ]);

        $response->assertStatus(401)->assertExactJson([
            'success' => false,
            'message' => 'Username or Password Is Wrong',
            'data' => []
        ]);
    }

    public function test_unauthenticated()
    {
        $response = $this->json('GET', '/api/v1/auth/me');
        $response->assertStatus(401)->assertExactJson([
            'message' => 'Unauthenticated.',
        ]);
    }

    public function test_authenticated()
    {
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/auth/me');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }
}
