<?php

use Illuminate\Http\Request;

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
Route::post('/NewAppRegister','NewController@NewAppRegister');
Route::post('/GetCategories','NewController@GetCategories');

/* CORE API */
Route::group( [ 'middleware' => [ 'check_api_token' ] ], function(){
	// Test methods
	Route::group( [ 'prefix' => '/check' ], function(){
		Route::any( '/api_key', [ 'as' => 'api.check.api_key', 'uses' => 'APICheckController@APIKey'] );
		Route::any( '/user_token', [ 'as' => 'api.check.user_token', 'middleware' => 'check_user_token', 'uses' => 'APICheckController@userToken'] );
	} );

	Route::group( [ 'prefix' => '/payments' ], function(){
		Route::any( '/ping', [ 'as' => 'api.payments.ping', 'uses' => 'APIPaymentController@ping'] );
		Route::any( '/paygate', [ 'as' => 'api.payments.paygate', 'uses' => 'APIPaymentController@paygate'] );
		Route::any( '/validate', [ 'as' => 'api.payments.validate_receipt', 'uses' => 'APIPaymentController@validateReceipt'] );
	} );

	Route::group( [ 'prefix' => '/app_installs' ], function(){
		Route::any( '/register', [ 'as' => 'api.app_installs.register', 'uses' => 'APIAppInstallsController@register'] );
	} );

	Route::group( [ 'prefix' => '/anonymous' ], function(){
		Route::any( '/create', [ 'as' => 'api.anonymous.create', 'uses' => 'APIAnonymousController@create'] );
	} );

	// User Token-protected methods
	Route::group( [ 'middleware' => 'check_user_token' ], function(){
		Route::group( [ 'prefix' => '/users' ], function(){
			Route::any( '/set', [ 'as' => 'api.users.set', 'uses' => 'APIUserController@setParameter'] );
			Route::any( '/show', [ 'as' => 'api.users.show', 'uses' => 'APIUserController@show'] );
			Route::any( '/add_search_ads_info', [ 'as' => 'api.users.add_search_ads_info', 'uses' => 'APIUserController@addSearchAdsInfo'] );
		} );
	} );

	// Anonymous & User Accessed endpoints
	Route::group( [ 'prefix' => '/categories' ], function(){
		Route::any( '/list', [ 'as' => 'api.categories.list', 'uses' => 'APICategoriesController@list'] );
	} );

	Route::group( [ 'prefix' => '/quote' ], function(){
		Route::any( '/', [ 'as' => 'api.quote', 'uses' => 'APIWallpapersController@quote'] );
	} );

	Route::group( [ 'prefix' => '/wallpapers' ], function(){
		Route::any( '/tags', [ 'as' => 'api.wallpapers.list', 'uses' => 'APIWallpapersController@tags'] );
		Route::any( '/list', [ 'as' => 'api.wallpapers.list', 'uses' => 'APIWallpapersController@list'] );
		Route::any( '/list_tag', [ 'as' => 'api.wallpapers.list_tag', 'uses' => 'APIWallpapersController@listByTag'] );
		Route::any( '/get', [ 'as' => 'api.wallpapers.get', 'uses' => 'APIWallpapersController@get', 'middleware' => 'check_payment' ] );
		Route::any( '/check', [ 'as' => 'api.wallpapers.check', 'uses' => 'APIWallpapersController@check', 'middleware' => 'check_payment' ] );
	} );
} );