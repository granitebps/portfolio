<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);


Route::group(['middleware' => ['auth'], 'prefix' => 'admin'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/profile/edit', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update', 'ProfileController@update')->name('profile.update');
    Route::get('/profile/password', 'ProfileController@password')->name('profile.password');
    Route::post('/profile/change-password', 'ProfileController@changePassword')->name('profile.changePassword');

    Route::get('/skill', 'SkillController@index')->name('skill.index');
    Route::get('/skill/create', 'SkillController@create')->name('skill.create');
    Route::post('/skill/store', 'SkillController@store')->name('skill.store');
    Route::get('/skill/{id}/edit', 'SkillController@edit')->name('skill.edit');
    Route::put('/skill/{id}', 'SkillController@update')->name('skill.update');
    Route::delete('/skill/{id}', 'SkillController@destroy')->name('skill.destroy');
});
