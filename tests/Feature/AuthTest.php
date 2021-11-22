<?php

use App\Models\Profile;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can login', function () {
    $profile = Profile::factory()->create();

    post('/api/v1/auth/login', [
        'username' => $profile->user->username,
        'password' => '12345678'
    ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => $profile->user->name,
                'avatar' => $profile->avatar
            ],
        ]);
});

it('cannot login using wrong password', function () {
    $profile = Profile::factory()->create();

    post('/api/v1/auth/login', [
        'username' => $profile->user->username,
        'password' => '1234567890'
    ])
        ->assertStatus(401);
});

it('can return user data with token', function () {
    $profile = Profile::factory()->create();
    $user = $profile->user;

    $token = $user->createToken(config('app.name'))->plainTextToken;

    get('/api/v1/auth/me', [
        'Authorization' => "Bearer $token",
    ])
        ->assertStatus(200)
        ->assertJson([
            'data' => $user->toArray()
        ]);
});

it('cannot get user data if not authenticated', function () {
    get('/api/v1/auth/me')
        ->assertStatus(401);
});
