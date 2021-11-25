<?php

use App\Models\Profile;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can login', function (Profile $profile) {
    post(
        uri: route('auth:login'),
        data: [
            'username' => $profile->user->username,
            'password' => '12345678'
        ]
    )
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => $profile->user->name,
                'avatar' => $profile->avatar
            ],
        ]);
})->with('profile');

it('cannot login using wrong password', function (Profile $profile) {
    post(
        uri: route('auth:login'),
        data: [
            'username' => $profile->user->username,
            'password' => '1234567890'
        ]
    )
        ->assertStatus(401);
})->with('profile');

it('can return user data with token', function (Profile $profile) {
    $user = $profile->user;

    $token = $user->createToken(config('app.name'))->plainTextToken;

    get(
        uri: route('auth:me'),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )
        ->assertStatus(200)
        ->assertJson([
            'data' => $user->toArray()
        ]);
})->with('profile');

it('cannot get user data if not authenticated', function () {
    get(
        uri: route('auth:me')
    )
        ->assertStatus(401);
});

it('can logout when authenticated', function (Profile $profile) {
    $user = $profile->user;

    $token = $user->createToken(config('app.name'))->plainTextToken;

    post(
        uri: route('auth:logout'),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )
        ->assertStatus(200);
})->with('profile');

it('cannot logout when unauthenticated', function () {
    post(
        uri: route('auth:logout'),
    )
        ->assertStatus(401);
});
