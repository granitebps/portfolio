<?php

use App\Models\Experience;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of experience', function () {
    get(
        uri: route('experience.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('experience');

it('can store a experience with authenticated user', function (Profile $profile) {
    $experience = Experience::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $this->assertDatabaseCount('experiences', 0);

    post(
        uri: route('experience.store'),
        data: [
            'company' => $experience->company,
            'position' => $experience->position,
            'start_date' => $experience->start_date,
            'end_date' => $experience->end_date,
            'current_job' => $experience->current_job,
            'desc' => $experience->desc
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('experience.store'),
        data: [
            'company' => $experience->company,
            'position' => $experience->position,
            'start_date' => $experience->start_date,
            'end_date' => $experience->end_date,
            'current_job' => $experience->current_job,
            'desc' => $experience->desc
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Experience::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a experience with authenticated user', function (Profile $profile, Experience $experience) {
    $newExperience = Experience::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Experience::find($experience->id))->toBeTruthy();

    put(
        uri: route('experience.update', [
            'experience' => 0
        ]),
        data: [
            'company' => $newExperience->company,
            'position' => $newExperience->position,
            'start_date' => $newExperience->start_date,
            'end_date' => $newExperience->end_date,
            'current_job' => $newExperience->current_job,
            'desc' => $newExperience->desc
        ],
    )->assertStatus(401);

    put(
        uri: route('experience.update', [
            'experience' => 0
        ]),
        data: [
            'company' => $newExperience->company,
            'position' => $newExperience->position,
            'start_date' => $newExperience->start_date,
            'end_date' => $newExperience->end_date,
            'current_job' => $newExperience->current_job,
            'desc' => $newExperience->desc
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('experience.update', [
            'experience' => $experience->id
        ]),
        data: [
            'company' => $newExperience->company,
            'position' => $newExperience->position,
            'start_date' => $newExperience->start_date,
            'end_date' => $newExperience->end_date,
            'current_job' => $newExperience->current_job,
            'desc' => $newExperience->desc
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($experience->id);
    expect(Experience::find($experience->id))->toBeTruthy();
    expect(Experience::find($experience->id))->company->toEqual($newExperience->company);
})->with('profile', 'experience');

it('can delete a experience with authenticated user', function (Profile $profile, Experience $experience) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Experience::find($experience->id))->toBeTruthy();

    delete(
        uri: route('experience.destroy', [
            'experience' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('experience.destroy', [
            'experience' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('experience.destroy', [
            'experience' => $experience->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Experience::find($experience->id))->toBeNull();
})->with('profile', 'experience');
