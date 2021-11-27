<?php

use App\Models\Profile;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

it('can get profile', function (Profile $profile) {
    $response = get(
        uri: route('profile.index'),
    )->assertStatus(200)->json();

    expect($response['data']['email'])->toEqual($profile->user->email);
})->with('profile');

it('can update profile when authenticated', function (Profile $profile) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;
    $faker = Faker::create();
    $newData = Profile::factory()->make();
    $file = UploadedFile::fake()->image('gallery.jpg');
    $email = $faker->safeEmail();

    post(
        uri: route('profile.update'),
    )->assertStatus(401);

    post(
        uri: route('profile.update'),
        headers: [
            'Authorization' => "Bearer $token",
        ],
        data: [
            'username' => $faker->userName(),
            'email' => $email,
            'name' => $faker->name(),
            'avatar' => $file,
            'about' => $newData->about,
            'phone' => $newData->phone,
            'address' => $newData->address,
            'nationality' => $newData->nationality,
            'languages' => $newData->languages,
            'freelance' => $newData->freelance,
            'instagram' => $newData->instagram,
            'facebook' => $newData->facebook,
            'twitter' => $newData->twitter,
            'youtube' => $newData->youtube,
            'github' => $newData->github,
            'linkedin' => $newData->linkedin,
            'medium' => $newData->medium,
            'birth' => $faker->dateTime()->format('Y-m-d H:i:s')
        ]
    )->assertStatus(200);

    expect(Profile::find($profile->id)->about)->toEqual($newData->about);
    expect(User::find($profile->user->id)->email)->toEqual($email);
})->with('profile');

it('can update password when authenticated', function (Profile $profile) {
    $token = $profile->user->createToken(config('app.name'))->plainTextToken;

    expect(Hash::check(12345678, $profile->user->password))->toBeTrue();

    post(
        uri: route('profile.password')
    )->assertStatus(401);

    post(
        uri: route('profile.password'),
        headers: [
            'Authorization' => "Bearer $token",
        ],
        data: [
            'password' => '12345abcde',
            'old_password' => '12345678',
            'password_confirmation' => 'abcde12345'
        ]
    )->assertStatus(422);

    post(
        uri: route('profile.password'),
        headers: [
            'Authorization' => "Bearer $token",
        ],
        data: [
            'password' => '12345abcde',
            'old_password' => '12345678',
            'password_confirmation' => '12345abcde'
        ]
    )->assertStatus(200);

    $user = User::find($profile->user->id);
    expect(Hash::check('12345abcde', $user->password))->toBeTrue();
})->with('profile');
