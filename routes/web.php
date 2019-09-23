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

Route::get('/', 'HomeController@welcome')->name('welcome');

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

    Route::get('/tech', 'TechnologyController@index')->name('tech.index');
    Route::get('/tech/create', 'TechnologyController@create')->name('tech.create');
    Route::post('/tech/store', 'TechnologyController@store')->name('tech.store');
    Route::get('/tech/{id}/edit', 'TechnologyController@edit')->name('tech.edit');
    Route::put('/tech/{id}', 'TechnologyController@update')->name('tech.update');
    Route::delete('/tech/{id}', 'TechnologyController@destroy')->name('tech.destroy');

    Route::get('/service', 'ServiceController@index')->name('service.index');
    Route::get('/service/create', 'ServiceController@create')->name('service.create');
    Route::post('/service/store', 'ServiceController@store')->name('service.store');
    Route::get('/service/{id}/edit', 'ServiceController@edit')->name('service.edit');
    Route::put('/service/{id}', 'ServiceController@update')->name('service.update');
    Route::delete('/service/{id}', 'ServiceController@destroy')->name('service.destroy');

    Route::get('/experience', 'ExperienceController@index')->name('experience.index');
    Route::get('/experience/create', 'ExperienceController@create')->name('experience.create');
    Route::post('/experience/store', 'ExperienceController@store')->name('experience.store');
    Route::get('/experience/{id}/edit', 'ExperienceController@edit')->name('experience.edit');
    Route::put('/experience/{id}', 'ExperienceController@update')->name('experience.update');
    Route::delete('/experience/{id}', 'ExperienceController@destroy')->name('experience.destroy');

    Route::get('/portfolio', 'PortfolioController@index')->name('portfolio.index');
    Route::get('/portfolio/create', 'PortfolioController@create')->name('portfolio.create');
    Route::post('/portfolio/store', 'PortfolioController@store')->name('portfolio.store');
    Route::get('/portfolio/{id}/edit', 'PortfolioController@edit')->name('portfolio.edit');
    Route::put('/portfolio/{id}', 'PortfolioController@update')->name('portfolio.update');
    Route::delete('/portfolio/{id}', 'PortfolioController@destroy')->name('portfolio.destroy');

    Route::get('/message', 'HomeController@getMessage')->name('message.index');
    Route::get('/message-delete/{id}', 'HomeController@deleteMessage')->name('message.delete');
});

Route::get('/preview', 'PortfolioController@preview')->name('portfolio.preview');
Route::post('/message', 'HomeController@message')->name('message');
