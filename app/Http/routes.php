<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::get('users/index', ['as' => 'users.index', 'uses' => 'UserController@index']);
Route::post('user/index', ['middleware' => 'ajax', 'as' => 'users.index', 'uses' => 'UserController@index']);
Route::post('user/create', ['middleware' => 'ajax', 'as' => 'users.create', 'uses' => 'UserController@create']);
Route::post('user/show', ['middleware' => 'ajax', 'as' => 'users.show', 'uses' => 'UserController@show']);
Route::post('user/store', ['middleware' => 'ajax', 'as' => 'users.store', 'uses' => 'UserController@store']);
Route::post('user/destroy', ['middleware' => 'ajax', 'as' => 'users.destroy', 'uses' => 'UserController@destroy']);

// 認証のルート定義…
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// 登録のルート定義…
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');