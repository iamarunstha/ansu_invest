<?php

	Route::group(['prefix'	=>	'admin/market-videos','route_group'=>'Market Videos', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\MarketVideos'], function() {
		
		Route::get('list',
		['as'	=>	'admin-market-videos-list-get',
		 'uses'	=>	'MarketVideosController@getListView',
		 'permission' => 'Market Videos List']);

		Route::get('create',
		['as'	=>	'admin-market-videos-create-get',
		 'uses'	=>	'MarketVideosController@getCreateView',
		 'permission' => 'Market Videos Create']);

		Route::post('create',
		['as'	=>	'admin-market-videos-create-post',
		 'uses'	=>	'MarketVideosController@postCreateView',
		 'permission' => 'Market Videos Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-market-videos-edit-get',
		 'uses'	=>	'MarketVideosController@getEditView',
		 'permission' => 'Market Videos Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-market-videos-edit-post',
		 'uses'	=>	'MarketVideosController@postEditView',
		 'permission' => 'Market Videos Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-market-videos-delete-post',
		 'uses'	=>	'MarketVideosController@postDeleteView',
		 'permission' => 'Market Videos Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-market-videos-delete-multiple-post',
		 'uses'	=>	'MarketVideosController@postDeleteMultipleView',
		 'permission' => 'Market Videos Delete']);

		Route::post('set-as-top-market-videos/{id}',
		['as'	=>	'admin-market-videos-set-as-top-market-videos-post',
		 'uses'	=>	'MarketVideosController@postSetAsTopMarketVideosView',
		 'permission' => 'Market Videos Feature/Unfeature']);
	});

	Route::group(['namespace' => '\App\Http\Controllers\Core\MarketVideos'], function() {
		Route::get('view-market-videos/{id}', [
			'as'	=>	'frontend-view-market-videos',
			'uses'	=>	'MarketVideosController@getViewMarketVideos'
		]);

		Route::get('market-videos', [
			'as'	=>	'frontend-market-videos',
			'uses'	=>	'MarketVideosController@getMarketVideos'
		]);
	});
