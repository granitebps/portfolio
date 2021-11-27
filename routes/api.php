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

Route::group(['namespace' => 'Api\v1', 'prefix' => 'v1', 'middleware' => ['cacheResponse']], function () {
    Route::post('auth/login', 'AuthController@login')->name('auth:login');

    Route::post('auth/request_reset_password', 'AuthController@request_reset_password');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('auth/me', 'AuthController@me')->name('auth:me');
        Route::post('auth/logout', 'AuthController@logout')->name('auth:logout');

        Route::post('profile', 'ProfileController@update');
        Route::post('profile-password', 'ProfileController@password');

        Route::apiResource('skill', 'SkillController')->except(['index', 'show']);

        Route::apiResource('service', 'ServiceController')->except(['index', 'show']);

        Route::apiResource('technology', 'TechnologyController')->except(['index', 'show']);

        Route::get('message', 'MessageController@index')->name('message.index');
        Route::delete('message/{id}', 'MessageController@destroy')->name('message.destroy');
        Route::get('message/read/{id}', 'MessageController@markRead')->name('message.read');

        Route::apiResource('portfolio', 'PortfolioController')->except(['index', 'show']);
        Route::get('portfolio-photo/{id}', 'PortfolioController@destroy_photo')->name('portfolio.photo');

        Route::apiResource('experience', 'ExperienceController')->except(['index', 'show']);

        Route::apiResource('education', 'EducationController')->except(['index', 'show']);

        Route::apiResource('blog', 'BlogController')->except(['index', 'show']);

        Route::apiResource('gallery', 'GalleryController')->except(['update', 'show']);

        Route::apiResource('certification', 'CertificationController')->except(['index', 'show']);

        Route::get('dashboard', 'DashboardController@dashboard');
    });

    Route::middleware([])->group(function () {
        Route::get('profile', 'ProfileController@index');

        Route::get('skill', 'SkillController@index');

        Route::get('service', 'ServiceController@index');

        Route::get('technology', 'TechnologyController@index');

        Route::post('message', 'MessageController@store')->name('message.store');

        Route::get('portfolio', 'PortfolioController@index')->name('portfolio.index');

        Route::get('experience', 'ExperienceController@index')->name('experience.index');

        Route::get('education', 'EducationController@index')->name('education.index');

        Route::get('blog', 'BlogController@index')->name('blog.index');
        Route::get('blog/{id}/{slug}', 'BlogController@show')->name('blog.show');

        Route::get('certification', 'CertificationController@index')->name('certification.index');
    });
});
Route::any('{path}', 'BaseController@not_found')->where('path', '.*');
