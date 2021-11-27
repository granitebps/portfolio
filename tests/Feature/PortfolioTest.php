<?php

use App\Models\Portfolio;
use App\Models\PortfolioPic;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of portfolio', function () {
    get(
        uri: route('portfolio.index'),
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('portfolio');

it('can store portfolio when authenticated', function (Profile $profile) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $portfolio = Portfolio::factory()->make();
    $file = UploadedFile::fake()->image('gallery.jpg');

    expect(Portfolio::count())->toEqual(0);
    expect(PortfolioPic::count())->toEqual(0);

    post(
        uri: route('portfolio.store'),
    )->assertStatus(401);

    $response = post(
        uri: route('portfolio.store'),
        data: [
            'name' => $portfolio->name,
            'desc' => $portfolio->desc,
            'type' => $portfolio->type,
            'url' => $portfolio->url,
            'thumbnail' => $file,
            'pic' => [
                $file
            ]
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(200);

    expect(Portfolio::find($response['data']['id']))->toBeTruthy();
    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(1);
})->with('profile');

it('can update portfolio when authenticated', function (Profile $profile, Portfolio $portfolio) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $newPortfolio = Portfolio::factory()->make();

    $file = UploadedFile::fake()->image('gallery.jpg');

    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(2);

    put(
        uri: route('portfolio.update', [
            'portfolio' => $portfolio->id
        ]),
        data: [
            'name' => $newPortfolio->name,
            'desc' => $newPortfolio->desc,
            'type' => $newPortfolio->type,
            'url' => $newPortfolio->url,
            'thumbnail' => $file,
            'pic' => [
                $file
            ]
        ],
    )->assertStatus(401);

    put(
        uri: route('portfolio.update', [
            'portfolio' => 0
        ]),
        data: [
            'name' => $newPortfolio->name,
            'desc' => $newPortfolio->desc,
            'type' => $newPortfolio->type,
            'url' => $newPortfolio->url,
            'thumbnail' => $file,
            'pic' => [
                $file
            ]
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(404);

    $response = put(
        uri: route('portfolio.update', [
            'portfolio' => $portfolio->id
        ]),
        data: [
            'name' => $newPortfolio->name,
            'desc' => $newPortfolio->desc,
            'type' => $newPortfolio->type,
            'url' => $newPortfolio->url,
            'thumbnail' => $file,
            'pic' => [
                $file
            ]
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(200);

    expect($response['data']['id'])->toEqual($portfolio->id);
    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(3);
})->with('profile', 'portfolio');

it('can delete portfolio when authenticated', function (Profile $profile, Portfolio $portfolio) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(2);

    delete(
        uri: route('portfolio.destroy', [
            'portfolio' => $portfolio->id
        ]),
    )->assertStatus(401);

    delete(
        uri: route('portfolio.destroy', [
            'portfolio' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(404);

    delete(
        uri: route('portfolio.destroy', [
            'portfolio' => $portfolio->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(200);

    expect(Portfolio::count())->toEqual(0);
    expect(PortfolioPic::count())->toEqual(0);
})->with('profile', 'portfolio');

it('can delete portfolio picture when authenticated', function (Profile $profile, Portfolio $portfolio) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    $pic = $portfolio->pic->first();

    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(2);

    get(
        uri: route('portfolio.photo', [
            'id' => $pic->id
        ]),
    )->assertStatus(401);

    get(
        uri: route('portfolio.photo', [
            'id' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(404);

    get(
        uri: route('portfolio.photo', [
            'id' => $pic->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ],
    )->assertStatus(200);

    expect(Portfolio::count())->toEqual(1);
    expect(PortfolioPic::count())->toEqual(1);
})->with('profile', 'portfolio');
