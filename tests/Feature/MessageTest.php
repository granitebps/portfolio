<?php

namespace Tests\Feature;

use App\Models\Message;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of message when authenticated', function (Profile $profile, Message $message) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    get(
        uri: route('message.index')
    )->assertStatus(401);

    get(
        uri: route('message.index'),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('data', 1)
            ->etc()
    );
})->with('profile', 'message');

it('can store message', function () {
    $message = Message::factory()->make();

    expect(Message::count())->toEqual(0);

    $response = post(
        uri: route('message.store'),
        data: [
            'first_name' => $message->first_name,
            'last_name' => $message->last_name,
            'email' => $message->email,
            'phone' => $message->phone,
            'message' => $message->message,
        ]
    )->assertStatus(200);

    expect(Message::count())->toEqual(1);
    expect(Message::find($response['data']['id']))->toBeTruthy();
});

it('can delete message when authenticated', function (Profile $profile, Message $message) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Message::where('id', $message->id)->count())->toEqual(1);

    delete(
        uri: route('message.destroy', [
            'id' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('message.destroy', [
            'id' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('message.destroy', [
            'id' => $message->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200);

    expect(Message::where('id', $message->id)->count())->toEqual(0);
})->with('profile', 'message');

it('can mark message as read when authenticated', function (Profile $profile, Message $message) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Message::where('id', $message->id)->count())->toEqual(1);
    expect(Message::find($message->id)->is_read)->toBeFalse();

    get(
        uri: route('message.read', [
            'id' => 0
        ]),
    )->assertStatus(401);

    get(
        uri: route('message.read', [
            'id' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    get(
        uri: route('message.read', [
            'id' => $message->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200);

    expect(Message::where('id', $message->id)->count())->toEqual(1);
    expect(Message::find($message->id)->is_read)->toBeTrue();
})->with('profile', 'message');
