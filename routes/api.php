<?php

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

Route::group(['namespace' => 'Api\v1', 'prefix' => 'v1', 'middleware' => 'cacheResponse'], function () {
    Route::post('auth/login', 'AuthController@login');

    Route::post('auth/request_reset_password', 'AuthController@request_reset_password');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('auth/me', 'AuthController@me');
        Route::post('auth/logout', 'AuthController@logout');

        Route::post('profile', 'ProfileController@update');
        Route::post('profile-password', 'ProfileController@password');

        Route::apiResource('skill', 'SkillController')->except(['index', 'show']);

        Route::apiResource('service', 'ServiceController')->except(['index', 'show']);

        Route::apiResource('technology', 'TechnologyController')->except(['index', 'show']);

        Route::get('message', 'MessageController@index');
        Route::delete('message/{id}', 'MessageController@destroy');
        Route::get('message/read/{id}', 'MessageController@markRead');

        Route::apiResource('portfolio', 'PortfolioController')->except(['index', 'show']);
        Route::get('portfolio-photo/{id}', 'PortfolioController@destroy_photo');

        Route::apiResource('experience', 'ExperienceController')->except(['index', 'show']);

        Route::apiResource('education', 'EducationController')->except(['index', 'show']);

        Route::apiResource('blog', 'BlogController')->except(['index', 'show']);

        Route::apiResource('gallery', 'GalleryController')->except(['update', 'show']);

        Route::apiResource('certification', 'CertificationController')->except(['index', 'show']);
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
    Route::get('blog/{id}/{slug}', 'BlogController@show');

    Route::get('certification', 'CertificationController@index');
});
Route::any('{path}', 'BaseController@not_found')->where('path', '.*');
