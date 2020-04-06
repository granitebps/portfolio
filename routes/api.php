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

Route::group(['namespace' => 'Api\v1', 'prefix' => 'v1'], function () {
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('auth/me', 'AuthController@me');
    });

    Route::get('skill', 'SkillController@index');
    Route::post('skill', 'SkillController@store');
    Route::put('skill/{id}', 'SkillController@update');
    Route::delete('skill/{id}', 'SkillController@destroy');

    Route::get('service', 'ServiceController@index');
    Route::post('service', 'ServiceController@store');
    Route::put('service/{id}', 'ServiceController@update');
    Route::delete('service/{id}', 'ServiceController@destroy');

    Route::get('profile', 'ProfileController@index');
    Route::post('profile', 'ProfileController@update');
    Route::post('profile-password', 'ProfileController@password');

    Route::get('technology', 'TechnologyController@index');
    Route::post('technology', 'TechnologyController@store');
    Route::put('technology/{id}', 'TechnologyController@update');
    Route::delete('technology/{id}', 'TechnologyController@destroy');
});
