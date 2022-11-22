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

include( __DIR__ . '/include/admin.php' );

Route::get('/', [ 'as' => 'front.index', 'uses' => 'FrontController@index'] );

// Страницы для авторизации
Route::group([ 'prefix'=>'/auth' ], function(){
	Route::get('/login', [ 'as' => 'front.auth.login', 'uses' => 'AuthController@login'] );
	Route::post('/login', [ 'as' => 'front.auth.login.do', 'uses' => 'AuthController@loginDo'] );
	Route::get('/logout', [ 'as' => 'front.auth.logout', 'uses' => 'AuthController@logout'] );
});

// Страницы с юридическим говном
Route::group([ 'prefix'=>'/legal' ], function(){
	Route::get('/policy', [ 'as' => 'front.legal.policy', 'uses' => 'LegalController@policy'] );
	Route::get('/terms', [ 'as' => 'front.legal.terms', 'uses' => 'LegalController@terms'] );
	Route::get('/contact', [ 'as' => 'front.legal.contact', 'uses' => 'LegalController@contact'] );
});

Route::group([ 'prefix'=>'/webhooks' ], function(){
	Route::any('/apple', [ 'as' => 'front.webhooks.apple', 'uses' => 'WebhooksController@apple'] );
});