<?php

use App\Models\Gallery;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Faker\faker;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can get list of gallery when authenticated', function (Profile $profile) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    get(
        uri: route('gallery.index'),
    )->assertStatus(401);

    get(
        uri: route('gallery.index'),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->assertJson(
        fn (AssertableJson $json) =>
        $json->has('data', 1)
            ->etc()
    );
})->with('profile', 'gallery');

it('can store a gallery when authenticated', function (Profile $profile) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $gallery = Gallery::factory()->make();

    $this->assertDatabaseCount('galeries', 0);

    post(
        uri: route('gallery.store'),
    )->assertStatus(401);

    $response = post(
        uri: route('gallery.store'),
        data: [
            'name' => $gallery->name,
            'ext' => $gallery->ext,
            'size' => $gallery->size,
            'file' => faker()->imageUrl()
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(200)->json();

    expect(Gallery::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can delete a gallery when authenticated', function (Profile $profile, Gallery $gallery) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Gallery::where('id', $gallery->id)->count())->toEqual(1);

    delete(
        uri: route('gallery.destroy', [
            'gallery' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('gallery.destroy', [
            'gallery' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('gallery.destroy', [
            'gallery' => $gallery->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200);

    expect(Gallery::where('id', $gallery->id)->count())->toEqual(0);
})->with('profile', 'gallery');
