<?php

	Route::group(['prefix'	=>	'admin/recommendations','route_group'=>'Recommendations', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Recommendations'], function() {
		
		Route::get('list',
		['as'	=>	'admin-recommendations-list-get',
		 'uses'	=>	'RecommendationsController@getListView',
		 'permission' => 'Recommendations List']);

		Route::get('create',
		['as'	=>	'admin-recommendations-create-get',
		 'uses'	=>	'RecommendationsController@getCreateView',
		 'permission' => 'Recommendations Create']);

		Route::post('create',
		['as'	=>	'admin-recommendations-create-post',
		 'uses'	=>	'RecommendationsController@postCreateView',
		 'permission' => 'Recommendations Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-recommendations-edit-get',
		 'uses'	=>	'RecommendationsController@getEditView',
		 'permission' => 'Recommendations Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-recommendations-edit-post',
		 'uses'	=>	'RecommendationsController@postEditView',
		 'permission' => 'Recommendations Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-recommendations-delete-post',
		 'uses'	=>	'RecommendationsController@postDeleteView',
		 'permission' => 'Recommendations Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-recommendations-delete-multiple-post',
		 'uses'	=>	'RecommendationsController@postDeleteMultipleView',
		 'permission' => 'Recommendations Delete']);

		Route::post('set-as-top-recommendations/{id}',
		['as'	=>	'admin-recommendations-set-as-top-recommendations-post',
		 'uses'	=>	'RecommendationsController@postSetAsTopRecommendationsView',
		 'permission' => 'Recommendations Set As Top']);
	});

	Route::group(['namespace' => '\App\Http\Controllers\Core\Recommendations'], function() {
		Route::get('view-recommendations/{id}', [
			'as'	=>	'frontend-view-recommendations',
			'uses'	=>	'RecommendationsController@getViewRecommendations'
		]);

		Route::get('recommendations', [
			'as'	=>	'frontend-recommendations',
			'uses'	=>	'RecommendationsController@getRecommendations'
		]);
	});
