<?php

use App\Models\Service;
use App\Models\Profile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of service', function () {
    get(
        uri: route('service.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('service');

it('can store a service with authenticated user', function (Profile $profile) {
    $service = Service::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $this->assertDatabaseCount('services', 0);

    post(
        uri: route('service.store'),
        data: [
            'name' => $service->name,
            'desc' => $service->desc,
            'icon' => $service->icon,
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('service.store'),
        data: [
            'name' => $service->name,
            'desc' => $service->desc,
            'icon' => $service->icon,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Service::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a service with authenticated user', function (Profile $profile, Service $service) {
    $newService = Service::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Service::find($service->id))->toBeTruthy();

    put(
        uri: route('service.update', [
            'service' => 0
        ]),
        data: [
            'name' => $newService->name,
            'desc' => $newService->desc,
            'icon' => $newService->icon,
        ],
    )->assertStatus(401);

    put(
        uri: route('service.update', [
            'service' => 0
        ]),
        data: [
            'name' => $newService->name,
            'desc' => $newService->desc,
            'icon' => $newService->icon,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('service.update', [
            'service' => $service->id
        ]),
        data: [
            'name' => $newService->name,
            'desc' => $newService->desc,
            'icon' => $newService->icon,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($service->id);
    expect(Service::find($service->id))->toBeTruthy();
    expect(Service::find($service->id))->name->toEqual($newService->name);
})->with('profile', 'service');

it('can delete a service with authenticated user', function (Profile $profile, Service $service) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Service::find($service->id))->toBeTruthy();

    delete(
        uri: route('service.destroy', [
            'service' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('service.destroy', [
            'service' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('service.destroy', [
            'service' => $service->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Service::find($service->id))->toBeNull();
})->with('profile', 'service');
