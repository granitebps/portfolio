<?php

use App\Models\Blog;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

it('can get list of blog', function () {
    get(
        uri: route('blog.index')
    )->assertStatus(200)
        ->assertJson(
            fn (AssertableJson $json) =>
            $json->has('data', 1)
                ->etc()
        );
})->with('blog');

it('can get a blog by id and slug', function (Blog $blog) {
    get(
        uri: route('blog.show', [
            'id' => $blog,
            'slug' => $blog->slug
        ])
    )->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $blog->id,
                'slug' => $blog->slug,
            ]
        ]);
})->with('blog');

it('can store a blog with authenticated user', function (Profile $profile) {
    $blog = Blog::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $file = UploadedFile::fake()->image('blog.jpg');

    $this->assertDatabaseCount('blogs', 0);

    post(
        uri: route('blog.store'),
        data: [
            'title' => $blog->title,
            'body' => $blog->body,
        ],
    )->assertStatus(401);

    $response = post(
        uri: route('blog.store'),
        data: [
            'title' => $blog->title,
            'body' => $blog->body,
            'image' => $file
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Blog::find($response['data']['id']))->toBeTruthy();
})->with('profile');

it('can update a blog with authenticated user', function (Profile $profile, Blog $blog) {
    $newBlog = Blog::factory()->make();
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $file = UploadedFile::fake()->image('blog.jpg');

    expect(Blog::find($blog->id))->toBeTruthy();

    put(
        uri: route('blog.update', [
            'blog' => 0
        ]),
        data: [
            'title' => $blog->title,
            'body' => $blog->body,
        ],
    )->assertStatus(401);

    put(
        uri: route('blog.update', [
            'blog' => 0
        ]),
        data: [
            'title' => $blog->title,
            'body' => $blog->body,
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    $response = put(
        uri: route('blog.update', [
            'blog' => $blog->id
        ]),
        data: [
            'title' => $newBlog->title,
            'body' => $newBlog->body,
            'image' => $file
        ],
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect($response['data']['id'])->toEqual($blog->id);
    expect(Blog::find($blog->id))->toBeTruthy();
    expect(Blog::find($blog->id))->title->toEqual($newBlog->title);
})->with('profile', 'blog');

it('can delete a blog with authenticated user', function (Profile $profile, Blog $blog) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Blog::find($blog->id))->toBeTruthy();

    delete(
        uri: route('blog.destroy', [
            'blog' => 0
        ]),
    )->assertStatus(401);

    delete(
        uri: route('blog.destroy', [
            'blog' => 0
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(404);

    delete(
        uri: route('blog.destroy', [
            'blog' => $blog->id
        ]),
        headers: [
            'Authorization' => "Bearer $token",
        ]
    )->assertStatus(200)->json();

    expect(Blog::find($blog->id))->toBeNull();
})->with('profile', 'blog');
