<?php

	Route::group(['prefix'	=>	'admin/experts', 'route_group'=>'Experts', 'middleware' => 'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Experts'], function() {
		
		Route::get('list',
		['as'	=>	'admin-experts-list-get',
		 'uses'	=>	'ExpertsController@getListView',
		 'permission' => 'Experts List']);

		Route::get('create',
		['as'	=>	'admin-experts-create-get',
		 'uses'	=>	'ExpertsController@getCreateView',
		 'permission' => 'Experts Create']);

		Route::post('create',
		['as'	=>	'admin-experts-create-post',
		 'uses'	=>	'ExpertsController@postCreateView',
		 'permission' => 'Experts Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-experts-edit-get',
		 'uses'	=>	'ExpertsController@getEditView',
		 'permission' => 'Experts Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-experts-edit-post',
		 'uses'	=>	'ExpertsController@postEditView',
		 'permission' => 'Experts Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-experts-delete-post',
		 'uses'	=>	'ExpertsController@postDeleteView',
		 'permission' => 'Experts Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-experts-delete-multiple-post',
		 'uses'	=>	'ExpertsController@postDeleteMultipleView',
		 'permission' => 'Experts Delete']);

		Route::post('set-as-top-experts/{id}',
		['as'	=>	'admin-experts-set-as-top-experts-post',
		 'uses'	=>	'ExpertsController@postSetAsTopExpertsView',
		 'permission' => 'Experts Set As Top']);
	});

	Route::group(['namespace' => '\App\Http\Controllers\Core\Experts'], function() {
		Route::get('view-experts/{id}', [
			'as'	=>	'frontend-view-experts',
			'uses'	=>	'ExpertsController@getViewExperts'
		]);

		Route::get('experts', [
			'as'	=>	'frontend-experts',
			'uses'	=>	'ExpertsController@getExperts'
		]);
	});