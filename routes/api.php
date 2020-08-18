<?php

use App\Simextrack;
use App\Traits\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('get-token', 'AuthController@get_token');;

Route::group(['namespace' => 'Api\v1', 'prefix' => 'v1'], function () {
    Route::post('auth/login', 'AuthController@login');

    Route::group(['middleware' => ['authToken']], function () {
        Route::get('auth/me', 'AuthController@me');

        Route::post('profile', 'ProfileController@update');
        Route::post('profile-password', 'ProfileController@password');

        Route::post('skill', 'SkillController@store');
        Route::put('skill/{id}', 'SkillController@update');
        Route::delete('skill/{id}', 'SkillController@destroy');

        Route::post('service', 'ServiceController@store');
        Route::put('service/{id}', 'ServiceController@update');
        Route::delete('service/{id}', 'ServiceController@destroy');

        Route::post('technology', 'TechnologyController@store');
        Route::put('technology/{id}', 'TechnologyController@update');
        Route::delete('technology/{id}', 'TechnologyController@destroy');

        Route::get('message', 'MessageController@index');
        Route::delete('message/{id}', 'MessageController@destroy');

        Route::post('portfolio', 'PortfolioController@store');
        Route::put('portfolio/{id}', 'PortfolioController@update');
        Route::delete('portfolio/{id}', 'PortfolioController@destroy');

        Route::post('experience', 'ExperienceController@store');
        Route::put('experience/{id}', 'ExperienceController@update');
        Route::delete('experience/{id}', 'ExperienceController@destroy');

        Route::post('education', 'EducationController@store');
        Route::put('education/{id}', 'EducationController@update');
        Route::delete('education/{id}', 'EducationController@destroy');

        Route::post('blog', 'BlogController@store');
        Route::put('blog/{id}', 'BlogController@update');
        Route::delete('blog/{id}', 'BlogController@destroy');

        Route::get('gallery', 'GalleryController@index');
        Route::post('gallery', 'GalleryController@store');
        Route::delete('gallery/{id}', 'GalleryController@destroy');
    });

    Route::get('profile', 'ProfileController@index');

    Route::get('skill', 'SkillController@index');

    Route::get('service', 'ServiceController@index');


    Route::get('technology', 'TechnologyController@index');

    Route::post('message', 'MessageController@store');

    Route::get('portfolio', 'PortfolioController@index');

    Route::get('experience', 'ExperienceController@index');

    Route::get('education', 'EducationController@index');

    Route::get('blog', 'BlogController@index');
    Route::get('blog/{id}', 'BlogController@show');

    Route::get('simextrack/v1', function (Request $request) {
        $check_ip = Simextrack::where('ip', $request->ip())->first();
        if (!$check_ip) {
            Simextrack::create([
                'ip' => $request->ip(),
                'version' => 'v1'
            ]);
        }

        return response()->download(public_path('images/simextrack/v1/simextrack-prod-v1.apk'));
    });

    Route::any('{path}', function () {
        return Helpers::apiResponse(false, 'Not Found', [], 404);
    })->where('path', '.*');
});
