<?php

	Route::group(['prefix'	=>	'admin/news', 'route_group'=>'News', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\News'], function() {
		
		Route::get('list/{news_type}',
		['as'	=>	'admin-news-list-get',
		 'uses'	=>	'NewsController@getListView',
		 'permission' => 'News List']);

		Route::get('create',
		['as'	=>	'admin-news-create-get',
		 'uses'	=>	'NewsController@getCreateView',
		 'permission' => 'News Create']);

		Route::post('create',
		['as'	=>	'admin-news-create-post',
		 'uses'	=>	'NewsController@postCreateView',
		 'permission' => 'News Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-news-edit-get',
		 'uses'	=>	'NewsController@getEditView',
		 'permission' => 'News Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-news-edit-post',
		 'uses'	=>	'NewsController@postEditView',
		 'permission' => 'News Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-news-delete-post',
		 'uses'	=>	'NewsController@postDeleteView',
		 'permission' => 'News Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-news-delete-multiple-post',
		 'uses'	=>	'NewsController@postDeleteMultipleView',
		 'permission' => 'News Delete']);

		Route::post('set-as-top-news/{id}',
		['as'	=>	'admin-news-set-as-top-news-post',
		 'uses'	=>	'NewsController@postSetAsTopNewsView' ,
		 'permission' => 'News Set As Top']);

		Route::post('set-as-news-board/{id}',
		['as'	=>	'admin-news-set-as-news-board-post',
		 'uses'	=>	'NewsController@postSetAsNewsBoardView',
		 'permission' => 'News Set As News Board']);
	});

	Route::group(['namespace' => '\App\Http\Controllers\Core\News'], function() {
		Route::get('view-news/{id}', [
			'as'	=>	'frontend-view-news',
			'uses'	=>	'NewsController@getViewNews'
		]);

		Route::get('news', [
			'as'	=>	'frontend-news',
			'uses'	=>	'NewsController@getNews'
		]);
	});
