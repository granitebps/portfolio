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

Route::get('get-token', 'AuthController@get_token');;

Route::group(['namespace' => 'Api\v1', 'prefix' => 'v1'], function () {
    Route::post('auth/login', 'AuthController@login');

    Route::group(['middleware' => ['authToken']], function () {
        Route::get('auth/me', 'AuthController@me');

        Route::post('profile', 'ProfileController@update');
        Route::post('profile-password', 'ProfileController@password');
    });
    Route::get('profile', 'ProfileController@index');

    Route::get('skill', 'SkillController@index');
    Route::post('skill', 'SkillController@store');
    Route::put('skill/{id}', 'SkillController@update');
    Route::delete('skill/{id}', 'SkillController@destroy');

    Route::get('service', 'ServiceController@index');
    Route::post('service', 'ServiceController@store');
    Route::put('service/{id}', 'ServiceController@update');
    Route::delete('service/{id}', 'ServiceController@destroy');


    Route::get('technology', 'TechnologyController@index');
    Route::post('technology', 'TechnologyController@store');
    Route::put('technology/{id}', 'TechnologyController@update');
    Route::delete('technology/{id}', 'TechnologyController@destroy');

    Route::get('message', 'MessageController@index');
    Route::post('message', 'MessageController@store');

    Route::get('portfolio', 'PortfolioController@index');
    Route::post('portfolio', 'PortfolioController@store');
    Route::put('portfolio/{id}', 'PortfolioController@update');
    Route::delete('portfolio/{id}', 'PortfolioController@destroy');

    Route::get('experience', 'ExperienceController@index');
    Route::post('experience', 'ExperienceController@store');
    Route::put('experience/{id}', 'ExperienceController@update');
    Route::delete('experience/{id}', 'ExperienceController@destroy');
});
