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

// Authentication Routes...
Route::get('auth/login', 'Auth\AuthController@login');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::group(['middleware' => 'pjax'], function () {
    Route::get('/', ['as' => 'lots.index', 'uses' => 'LotController@index']);
    Route::get('/history', ['as' => 'lots.history', 'uses' => 'LotController@history']);
    Route::get('/profile', [
        'as' => 'profile.index',
        'uses' => 'ProfileController@index'
    ]);
    Route::get('/profile/lots', [
        'as' => 'profile.lots',
        'uses' => 'ProfileController@lots'
    ]);
    Route::patch('/profile/update/tradeUrl', [
        'as' => 'profile.update.tradeUrl',
        'uses' => 'ProfileController@updateTradeURL'
    ]);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'acl'], 'can' => 'admin.access'], function () {
    Route::get('/', 'AdminController@index');
});