<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'BaseController@index');
Route::get('reset_password/{token}', 'Api\v1\AuthController@reset_password_form')->name('auth.password.reset.view');
Route::post('reset_password', 'Api\v1\AuthController@reset_password')->name('reset_password');
