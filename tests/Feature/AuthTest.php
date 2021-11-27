<?php

use App\Models\Profile;
use App\Models\ResetPassword;
use App\Notifications\ResetPasswordNotification;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Notification;

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

it('can request reset password when authenticated', function (Profile $profile) {
    $faker = Faker::create();
    Notification::fake();

    post(
        uri: route('auth.password.reset.request'),
        data: [
            'email' => $faker->safeEmail()
        ]
    )->assertStatus(422);

    post(
        uri: route('auth.password.reset.request'),
        data: [
            'email' => $profile->user->email
        ]
    )->assertStatus(200);

    expect(ResetPassword::where('user_id', $profile->user->id)->count())->toEqual(1);

    Notification::assertSentTo(
        [$profile->user],
        ResetPasswordNotification::class
    );
})->with('profile');

it('it can redirect to request password view', function (Profile $profile) {
    $reset = ResetPassword::factory()->create([
        'user_id' => $profile->user->id,
        'expired_at' => now()->addDay(),
        'is_valid' => true
    ]);

    get(
        uri: route('auth.password.reset.view', [
            'token' => '12345'
        ]),
    )
        ->assertViewIs('reset_password')
        ->assertViewHas('is_valid', false);

    get(
        uri: route('auth.password.reset.view', [
            'token' => $reset->token
        ]),
    )
        ->assertViewIs('reset_password')
        ->assertViewHas('is_valid', true)
        ->assertViewHas('token', $reset->token);
})->with('profile');

it('it can reset password', function (Profile $profile) {
    $reset = ResetPassword::factory()->create([
        'user_id' => $profile->user->id,
        'expired_at' => now()->addDay(),
        'is_valid' => true
    ]);

    $reset2 = ResetPassword::factory()->create([
        'user_id' => $profile->user->id,
        'expired_at' => now()->addDay(),
        'is_valid' => false
    ]);

    post(
        uri: route('reset_password'),
        data: [
            'token' => '12345',
            'password' => '1234567890',
            'password_confirmation' => '12345678'
        ]
    )->assertStatus(302);

    post(
        uri: route('reset_password'),
        data: [
            'token' => $reset2->token,
            'password' => '1234567890',
            'password_confirmation' => '1234567890'
        ]
    )
        ->assertViewIs('reset_password')
        ->assertViewHas('is_valid', false);

    post(
        uri: route('reset_password'),
        data: [
            'token' => $reset->token,
            'password' => '1234567890',
            'password_confirmation' => '1234567890'
        ]
    )
        ->assertViewIs('reset_password')
        ->assertViewHas('success', true);
})->with('profile');
