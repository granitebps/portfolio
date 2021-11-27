<?php

use App\Models\Education;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of education', function () {
    get(
        uri: route('education.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('education');

it('can store a education with authenticated user', function (Profile $profile) {
    $education = Education::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $this->assertDatabaseCount('educations', 0);

    post(
        uri: route('education.store'),
        data: [
            'name' => $education->name,
            'institute' => $education->institute,
            'start_year' => $education->start_year,
            'end_year' => $education->end_year,
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('education.store'),
        data: [
            'name' => $education->name,
            'institute' => $education->institute,
            'start_year' => $education->start_year,
            'end_year' => $education->end_year,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Education::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a education with authenticated user', function (Profile $profile, Education $education) {
    $newEducation = Education::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Education::find($education->id))->toBeTruthy();

    put(
        uri: route('education.update', [
            'education' => 0
        ]),
        data: [
            'name' => $newEducation->name,
            'institute' => $newEducation->institute,
            'start_year' => $newEducation->start_year,
            'end_year' => $newEducation->end_year,
        ],
    )->assertStatus(401);

    put(
        uri: route('education.update', [
            'education' => 0
        ]),
        data: [
            'name' => $newEducation->name,
            'institute' => $newEducation->institute,
            'start_year' => $newEducation->start_year,
            'end_year' => $newEducation->end_year,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('education.update', [
            'education' => $education->id
        ]),
        data: [
            'name' => $newEducation->name,
            'institute' => $newEducation->institute,
            'start_year' => $newEducation->start_year,
            'end_year' => $newEducation->end_year,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($education->id);
    expect(Education::find($education->id))->toBeTruthy();
    expect(Education::find($education->id))->name->toEqual($newEducation->name);
})->with('profile', 'education');

it('can delete a education with authenticated user', function (Profile $profile, Education $education) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Education::find($education->id))->toBeTruthy();

    delete(
        uri: route('education.destroy', [
            'education' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('education.destroy', [
            'education' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('education.destroy', [
            'education' => $education->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Education::find($education->id))->toBeNull();
})->with('profile', 'education');
