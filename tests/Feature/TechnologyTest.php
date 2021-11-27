<?php

use App\Models\Technology;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of technology', function () {
    get(
        uri: route('technology.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('technology');

it('can store a technology with authenticated user', function (Profile $profile) {
    $technology = Technology::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $file = UploadedFile::fake()->image('gallery.jpg');

    expect(Technology::count())->toEqual(0);

    post(
        uri: route('technology.store'),
        data: [
            'name' => $technology->name,
            'pic' => $file,
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('technology.store'),
        data: [
            'name' => $technology->name,
            'pic' => $file,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Technology::find($response['data']['id']))->toBeTruthy();
    expect(Technology::count())->toEqual(1);
})->with('profile');

it('can update a technology with authenticated user', function (Profile $profile, Technology $technology) {
    $newTechnology = Technology::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $file = UploadedFile::fake()->image('gallery.jpg');

    expect(Technology::find($technology->id))->toBeTruthy();

    put(
        uri: route('technology.update', [
            'technology' => 0
        ]),
        data: [
            'name' => $newTechnology->name,
            'pic' => $file,
        ],
    )->assertStatus(401);

    put(
        uri: route('technology.update', [
            'technology' => 0
        ]),
        data: [
            'name' => $newTechnology->name,
            'pic' => $file,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('technology.update', [
            'technology' => $technology->id
        ]),
        data: [
            'name' => $newTechnology->name,
            'pic' => $file,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($technology->id);
    expect(Technology::find($technology->id))->toBeTruthy();
    expect(Technology::find($technology->id))->name->toEqual($newTechnology->name);
})->with('profile', 'technology');

it('can delete a technology with authenticated user', function (Profile $profile, Technology $technology) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Technology::find($technology->id))->toBeTruthy();

    delete(
        uri: route('technology.destroy', [
            'technology' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('technology.destroy', [
            'technology' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('technology.destroy', [
            'technology' => $technology->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Technology::find($technology->id))->toBeNull();
})->with('profile', 'technology');
