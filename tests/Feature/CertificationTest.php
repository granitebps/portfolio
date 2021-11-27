<?php

use App\Models\Certification;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of certification', function () {
    get(
        uri: route('certification.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('certification');

it('can store a certification with authenticated user', function (Profile $profile) {
    $certification = Certification::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $this->assertDatabaseCount('certifications', 0);

    post(
        uri: route('certification.store'),
        data: [
            'name' => $certification->name,
            'institution' => $certification->institution,
            'link' => $certification->link,
            'published' => $certification->published
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('certification.store'),
        data: [
            'name' => $certification->name,
            'institution' => $certification->institution,
            'link' => $certification->link,
            'published' => $certification->published
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Certification::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a certification with authenticated user', function (Profile $profile, Certification $certification) {
    $newCertification = certification::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Certification::find($certification->id))->toBeTruthy();

    put(
        uri: route('certification.update', [
            'certification' => 0
        ]),
        data: [
            'name' => $newCertification->name,
            'institution' => $newCertification->institution,
            'link' => $newCertification->link,
            'published' => $newCertification->published
        ],
    )->assertStatus(401);

    put(
        uri: route('certification.update', [
            'certification' => 0
        ]),
        data: [
            'name' => $newCertification->name,
            'institution' => $newCertification->institution,
            'link' => $newCertification->link,
            'published' => $newCertification->published
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('certification.update', [
            'certification' => $certification->id
        ]),
        data: [
            'name' => $newCertification->name,
            'institution' => $newCertification->institution,
            'link' => $newCertification->link,
            'published' => $newCertification->published
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($certification->id);
    expect(Certification::find($certification->id))->toBeTruthy();
    expect(Certification::find($certification->id))->name->toEqual($newCertification->name);
})->with('profile', 'certification');

it('can delete a certification with authenticated user', function (Profile $profile, Certification $certification) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Certification::find($certification->id))->toBeTruthy();

    delete(
        uri: route('certification.destroy', [
            'certification' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('certification.destroy', [
            'certification' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('certification.destroy', [
            'certification' => $certification->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Certification::find($certification->id))->toBeNull();
})->with('profile', 'certification');
