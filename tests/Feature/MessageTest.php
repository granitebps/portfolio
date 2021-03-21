<?php

namespace Tests\Feature;

use App\Models\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\AuthTraitTest;

class MessageTest extends TestCase
{
    use DatabaseTransactions, AuthTraitTest;

    /** @test */
    public function test_get_message()
    {
        $response = $this->json('GET', '/api/v1/message');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/message');
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => ''
        ]);
    }

    /** @test */
    public function test_create_message()
    {
        $response = $this->json('POST', '/api/v1/message', $this->messageData());
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Message Created'
        ]);
    }

    /** @test */
    public function test_delete_message()
    {
        $response = $this->json('DELETE', '/api/v1/message/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/message/99');
        $response->assertStatus(404);

        $message = $this->createMessage();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('DELETE', '/api/v1/message/' . $message->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Message Deleted'
        ]);
    }

    /** @test */
    public function test_mark_message_as_read()
    {
        $response = $this->json('GET', '/api/v1/message/read/99');
        $response->assertStatus(401);

        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/message/read/99');
        $response->assertStatus(404);

        $message = $this->createMessage();
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->json('GET', '/api/v1/message/read/' . $message->id);
        $response->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Message Mark As Read'
        ]);
    }

    public function messageData()
    {
        return [
            'first_name' => 'Test First Name',
            'last_name' => 'Test Last Name',
            'email' => 'test@test.test',
            'phone' => 'Test Phone',
            'message' => 'Test Message',
        ];
    }

    public function createMessage()
    {
        $message = Message::create($this->messageData());
        return $message;
    }
}
