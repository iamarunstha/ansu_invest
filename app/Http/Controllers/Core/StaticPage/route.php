<?php

	Route::group(['prefix'	=>	'admin/static', 'route_group'=>'Definitions', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\StaticPage'], function() {

		Route::get('list',
		['as'	=>	'admin-static-list-get',
		 'uses'	=>	'StaticPageController@getListView',
		 'permission' => 'Static Page List']);

		Route::get('update/{id}/{initial?}',
		['as'	=>	'admin-static-update-get',
		 'uses'	=>	'StaticPageController@getUpdate',
		 'permission' => 'Static Page Check inside']);

		Route::post('update/{id}/{initial}',
		['as'	=>	'admin-static-update-post',
		 'uses'	=>	'StaticPageController@postUpdate',
		 'permission' => 'Static Page Update']);

		Route::post('create/{page_id}',
		['as'	=>	'admin-definition-create-post',
		 'uses' =>	'StaticPageController@postCreate',
		 'permission' => 'New Terms Create']);

		Route::post('delete/{term_id}',
		['as'	=>	'admin-definition-delete-post',
		 'uses'	=>	'StaticPageController@postDelete',
		 'permission' => 'Delete Terms']);

		Route::post('delete-multiple',
		['as'	=>	'admin-definitions-delete-multiple-post',
		 'uses'	=>	'StaticPageController@postDeleteMultiple',
		 'permission' => 'Delete Terms']);
	});
