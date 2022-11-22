<?php

// Административные ресурсы
Route::group( [ 'prefix'=>'/admin', 'middleware'=>[ 'check_admin' ] ], function(){
	// Главная страница
	Route::get( '/', [ 'as'=>'admin.index', 'uses'=>'AdminIndexController@index' ]);
	Route::post( '/delete_notice/{id}', [ 'as'=>'admin.delete_notice.do', 'uses'=>'AdminIndexController@deleteNoticeDo' ]);

	// Настройки приложения
	Route::group([ 'prefix'=>'/app_settings' ], function(){
		Route::get('/', [ 'as' => 'admin.app_settings.show', 'uses' => 'AdminAppSettingsController@show'] );
		Route::get('/edit', [ 'as' => 'admin.app_settings.edit', 'uses' => 'AdminAppSettingsController@edit'] );
		Route::post('/edit', [ 'as' => 'admin.app_settings.edit.do', 'uses' => 'AdminAppSettingsController@editDo'] );
	} );

	Route::group([ 'prefix'=>'/push_notifications' ], function(){
		Route::get('/send/{notification_key}', [ 'as' => 'admin.push_notifications.send', 'uses' => 'AdminPushNotificationsController@send'] );
		Route::post('/send', [ 'as' => 'admin.push_notifications.send.do', 'uses' => 'AdminPushNotificationsController@sendDo'] );
	} );

	// Все анонимные записи
	Route::group([ 'prefix'=>'/anonymous' ], function(){
		Route::get('/', [ 'as' => 'admin.anonymous.index', 'uses' => 'AdminAnonymousController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.anonymous.delete.do', 'uses' => 'AdminAnonymousController@deleteDo'] );
		Route::post('/purge', [ 'as' => 'admin.anonymous.purge.do', 'uses' => 'AdminAnonymousController@purge'] );
	} );

	Route::group([ 'prefix'=>'/app_settings' ], function(){
		Route::get('/', [ 'as' => 'admin.app_settings.index', 'uses' => 'AdminAppSettingsController@index'] );
		Route::get('/edit_version/{version_id}', [ 'as' => 'admin.app_versions.edit', 'uses' => 'AdminAppSettingsController@editVersion'] );
		Route::post('/edit_version/{version_id}', [ 'as' => 'admin.app_versions.edit.do', 'uses' => 'AdminAppSettingsController@editVersionDo'] );
		Route::post('/delete_version/{version_id}', [ 'as' => 'admin.app_versions.delete.do', 'uses' => 'AdminAppSettingsController@deleteVersionDo'] );
		Route::get('/edit_settings/{settings_id}', [ 'as' => 'admin.app_settings.edit', 'uses' => 'AdminAppSettingsController@editSettings'] );
		Route::post('/edit_settings/{settings_id}', [ 'as' => 'admin.app_settings.edit.do', 'uses' => 'AdminAppSettingsController@editSettingsDo'] );
		Route::post('/delete_settings/{settings_id}', [ 'as' => 'admin.app_settings.delete.do', 'uses' => 'AdminAppSettingsController@deleteSettingsDo'] );
	} );

	Route::group([ 'prefix'=>'/users' ], function(){
		Route::get('/', [ 'as' => 'admin.users.index', 'uses' => 'AdminUsersController@index'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.users.edit', 'uses' => 'AdminUsersController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.users.edit.do', 'uses' => 'AdminUsersController@editDo'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.users.delete.do', 'uses' => 'AdminUsersController@deleteDo'] );
		Route::get('/show/{id}', [ 'as' => 'admin.users.show', 'uses' => 'AdminUsersController@show'] );
		Route::post('/delete_token/{id}', [ 'as' => 'admin.users.delete_token', 'uses' => 'AdminUsersController@deleteTokenDo'] );
		Route::get('/tester_mark/{id}', [ 'as' => 'admin.users.tester_mark', 'uses' => 'AdminUsersController@testerMark'] );
		Route::post('/purge', [ 'as' => 'admin.users.purge.do', 'uses' => 'AdminUsersController@purge'] );
	} );

	// Вечные покупки
	Route::group([ 'prefix'=>'/non_consumables' ], function(){
		Route::get('/', [ 'as' => 'admin.non_consumables.index', 'uses' => 'AdminNonConsumablesController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.non_consumables.delete.do', 'uses' => 'AdminNonConsumablesController@deleteDo'] );
		Route::get('/show/{id}', [ 'as' => 'admin.non_consumables.show', 'uses' => 'AdminNonConsumablesController@show'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.non_consumables.edit', 'uses' => 'AdminNonConsumablesController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.non_consumables.edit.do', 'uses' => 'AdminNonConsumablesController@editDo'] );
	} );

	// Подписки
	Route::group([ 'prefix'=>'/subscriptions' ], function(){
		Route::get('/', [ 'as' => 'admin.subscriptions.index', 'uses' => 'AdminSubscriptionsController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.subscriptions.delete.do', 'uses' => 'AdminSubscriptionsController@deleteDo'] );
		Route::get('/show/{id}', [ 'as' => 'admin.subscriptions.show', 'uses' => 'AdminSubscriptionsController@show'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.subscriptions.edit', 'uses' => 'AdminSubscriptionsController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.subscriptions.edit.do', 'uses' => 'AdminSubscriptionsController@editDo'] );
		Route::get('/edit_revenue/{subscription_id}/{id}', [ 'as' => 'admin.subscriptions.edit_revenue', 'uses' => 'AdminSubscriptionsController@editRevenue'] );
		Route::post('/edit_revenue/{subscription_id}/{id}', [ 'as' => 'admin.subscriptions.edit_revenue.do', 'uses' => 'AdminSubscriptionsController@editRevenueDo'] );
		Route::post('/delete_revenue/{id}', [ 'as' => 'admin.subscriptions.delete_revenue.do', 'uses' => 'AdminSubscriptionsController@deleteRevenueDo'] );
	} );

	// Бесплатные доступы
	Route::group([ 'prefix'=>'/free_access' ], function(){
		Route::get('/', [ 'as' => 'admin.free_access.index', 'uses' => 'AdminFreeAccessController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.free_access.delete.do', 'uses' => 'AdminFreeAccessController@deleteDo'] );
		Route::get('/show/{id}', [ 'as' => 'admin.free_access.show', 'uses' => 'AdminFreeAccessController@show'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.free_access.edit', 'uses' => 'AdminFreeAccessController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.free_access.edit.do', 'uses' => 'AdminFreeAccessController@editDo'] );
	} );

	// Чеки пользователей
	Route::group([ 'prefix'=>'/receipts' ], function(){
		Route::get('/', [ 'as' => 'admin.receipts.index', 'uses' => 'AdminReceiptsController@index'] );
		Route::get('/show/{id}', [ 'as' => 'admin.receipts.show', 'uses' => 'AdminReceiptsController@show'] );
		Route::get('/stats', [ 'as' => 'admin.receipts.stats', 'uses' => 'AdminReceiptsController@stats'] );
		Route::get('/billing_retries', [ 'as' => 'admin.receipts.billing_retries', 'uses' => 'AdminReceiptsController@billingRetries'] );
		Route::any('/validate/{id}', [ 'as' => 'admin.receipts.validate', 'uses' => 'AdminReceiptsController@validateReceipt'] );
		Route::any('/validate/{id}/{raw}', [ 'as' => 'admin.receipts.validate_raw', 'uses' => 'AdminReceiptsController@validateReceipt'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.receipts.delete.do', 'uses' => 'AdminReceiptsController@deleteDo'] );
	} );

	// Регистрации установок
	Route::group([ 'prefix'=>'/app_installs' ], function(){
		Route::get('/', [ 'as' => 'admin.app_installs.index', 'uses' => 'AdminAppInstallsController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.app_installs.delete.do', 'uses' => 'AdminAppInstallsController@deleteDo'] );
	} );

	// Тэги
	Route::group([ 'prefix'=>'/tags' ], function(){
		Route::get('/', [ 'as' => 'admin.tags.index', 'uses' => 'AdminTagsController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.tags.delete.do', 'uses' => 'AdminTagsController@deleteDo'] );
		Route::get('/show/{id}', [ 'as' => 'admin.tags.show', 'uses' => 'AdminTagsController@show'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.tags.edit', 'uses' => 'AdminTagsController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.tags.edit.do', 'uses' => 'AdminTagsController@editDo'] );
	} );

	// Категории
	Route::group([ 'prefix'=>'/categories' ], function(){
		Route::get('/', [ 'as' => 'admin.categories.index', 'uses' => 'AdminCategoriesController@index'] );
		Route::get('/sort', [ 'as' => 'admin.categories.sort', 'uses' => 'AdminCategoriesController@sort'] );
		Route::post('/sort', [ 'as' => 'admin.categories.sort.do', 'uses' => 'AdminCategoriesController@sortDo'] );
		Route::get('/sort_wallpapers/{category_id}', [ 'as' => 'admin.categories.sort_wallpapers', 'uses' => 'AdminCategoriesController@sortWallpapers'] );
		Route::post('/sort_wallpapers', [ 'as' => 'admin.categories.sort_wallpapers.do', 'uses' => 'AdminCategoriesController@sortWallpapersDo'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.categories.edit', 'uses' => 'AdminCategoriesController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.categories.edit.do', 'uses' => 'AdminCategoriesController@editDo'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.categories.delete.do', 'uses' => 'AdminCategoriesController@deleteDo'] );
	} );


	// Цитаты внутри категории
	Route::group([ 'prefix'=>'/category_quotes' ], function(){
		Route::get('/', [ 'as' => 'admin.category_quotes.index', 'uses' => 'AdminCategoryQuotesController@index'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.category_quotes.delete.do', 'uses' => 'AdminCategoryQuotesController@deleteDo'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.category_quotes.edit', 'uses' => 'AdminCategoryQuotesController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.category_quotes.edit.do', 'uses' => 'AdminCategoryQuotesController@editDo'] );
	} );

	// Обои
	Route::group([ 'prefix'=>'/wallpapers' ], function(){
		Route::get('/', [ 'as' => 'admin.wallpapers.index', 'uses' => 'AdminWallpapersController@index'] );
		Route::get('/listing/{cat_id}', [ 'as' => 'admin.wallpapers.listing', 'uses' => 'AdminWallpapersController@listing'] );
		Route::get('/gallery/{cat_id}', [ 'as' => 'admin.wallpapers.gallery', 'uses' => 'AdminWallpapersController@gallery'] );
		Route::post('/all', [ 'as' => 'admin.wallpapers.all.do', 'uses' => 'AdminWallpapersController@allDo'] );
		Route::post('/selected', [ 'as' => 'admin.wallpapers.selected.do', 'uses' => 'AdminWallpapersController@selectedDo'] );
		Route::get('/edit/{id}', [ 'as' => 'admin.wallpapers.edit', 'uses' => 'AdminWallpapersController@edit'] );
		Route::post('/edit/{id}', [ 'as' => 'admin.wallpapers.edit.do', 'uses' => 'AdminWallpapersController@editDo'] );
		Route::post('/delete/{id}', [ 'as' => 'admin.wallpapers.delete.do', 'uses' => 'AdminWallpapersController@deleteDo'] );
	} );

	Route::group([ 'prefix'=>'/cache' ], function(){
		Route::get('/index', [ 'as' => 'admin.cache.index', 'uses' => 'AdminCacheController@index'] );
		Route::post('/index', [ 'as' => 'admin.cache.index.do', 'uses' => 'AdminCacheController@indexDo'] );
		Route::get('/categories_list', [ 'as' => 'admin.cache.categories_list', 'uses' => 'AdminCacheController@categoriesList'] );
	} );
});