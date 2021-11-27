<?php

use App\Models\Skill;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of skill', function () {
    get(
        uri: route('skill.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('skill');

it('can store a skill with authenticated user', function (Profile $profile) {
    $skill = Skill::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $this->assertDatabaseCount('skills', 0);

    post(
        uri: route('skill.store'),
        data: [
            'name' => $skill->name,
            'percentage' => $skill->percentage,
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('skill.store'),
        data: [
            'name' => $skill->name,
            'percentage' => $skill->percentage,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Skill::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a skill with authenticated user', function (Profile $profile, Skill $skill) {
    $newSkill = Skill::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Skill::find($skill->id))->toBeTruthy();

    put(
        uri: route('skill.update', [
            'skill' => 0
        ]),
        data: [
            'name' => $newSkill->name,
            'percentage' => $newSkill->percentage,
        ],
    )->assertStatus(401);

    put(
        uri: route('skill.update', [
            'skill' => 0
        ]),
        data: [
            'name' => $newSkill->name,
            'percentage' => $newSkill->percentage,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('skill.update', [
            'skill' => $skill->id
        ]),
        data: [
            'name' => $newSkill->name,
            'percentage' => $newSkill->percentage,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($skill->id);
    expect(Skill::find($skill->id))->toBeTruthy();
    expect(Skill::find($skill->id))->name->toEqual($newSkill->name);
})->with('profile', 'skill');

it('can delete a skill with authenticated user', function (Profile $profile, Skill $skill) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Skill::find($skill->id))->toBeTruthy();

    delete(
        uri: route('skill.destroy', [
            'skill' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('skill.destroy', [
            'skill' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('skill.destroy', [
            'skill' => $skill->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Skill::find($skill->id))->toBeNull();
})->with('profile', 'skill');
