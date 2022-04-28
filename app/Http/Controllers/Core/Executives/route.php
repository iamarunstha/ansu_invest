<?php

Route::group(['prefix'	=>	'admin/executives', 'route_group'=>'Executives', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Executives'], function() {

		Route::get('list-all/',
		['as'	=>  'admin-executives-list-all-get',
	 	'uses'	=>	'ExecutivesController@getListAllView',
		 'permission' => 'Company List for executives']);

	 	Route::get('list-column/',
	 	['as'	=>  'admin-executives-column-list-get',
	 	'uses'	=>	'ExecutivesController@getListColumnsView',
		 'permission' => 'Executives Column List']);

	 	Route::get('create-column/',
	 	['as'	=>  'admin-executives-column-create-get',
	 	'uses'	=>	'ExecutivesController@getCreateColumnsView',
		 'permission' => 'Executives Create Column']);

	 	Route::post('create-column/',
	 	['as'	=>  'admin-executives-column-create-post',
	 	'uses'	=>	'ExecutivesController@postCreateColumnsView',
		 'permission' => 'Executives Create Column']);

	 	Route::get('edit-column/{column_id}',
	 	['as'	=>  'admin-executives-column-edit-get',
	 	'uses'	=>	'ExecutivesController@getEditColumnsView',
		 'permission' => 'Executives Edit Column']);

	 	Route::post('edit-column/{column_id}',
	 	['as'	=>  'admin-executives-column-edit-post',
	 	'uses'	=>	'ExecutivesController@postEditColumnsView',
		 'permission' => 'Executives Edit Column']);

		Route::post('delete-column/{column_id}',
	 	['as'	=>  'admin-executives-column-delete-post',
	 	'uses'	=>  'ExecutivesController@postDeleteColumnView',
		 'permission' => 'Executives Delete Column']);

	 	Route::post('delete-multiple-column/',
	 	['as'	=>	'admin-executives-column-delete-multiple-post',
	 	'uses'	=>  'ExecutivesController@postDeleteMultipleColumnView',
		 'permission' => 'Executives Delete Column']);

	 	Route::get('list-tab/',
	 	['as'	=>  'admin-executives-tab-list-get',
	 	'uses'	=>  'ExecutivesController@getTabListView',
		 'permission' => 'Executives List Tabs']);

	 	Route::post('create-tab/',
	 	['as'	=>  'admin-executives-tab-create-post',
	 	'uses'	=>  'ExecutivesController@postTabCreateView',
		 'permission' => 'Executives Create Tab']);

	 	Route::post('delete-tab/{tab_id}',
	 	['as'	=>	'admin-executives-tab-delete-post',
	 	'uses'  =>	'ExecutivesController@postTabDeleteView',
		 'permission' => 'Executives Delete Tab']);

	 	Route::post('edit-tab/{tab_id}',
	 	['as'	=>	'admin-executives-tab-edit-post',
	 	'uses'	=>	'ExecutivesController@postTabEditView',
		 'permission' => 'Executives Edit Tab']);

	 	Route::get('list/{company_id}',
		['as'	=>  'admin-executives-list-get',
	 	'uses'	=>	'ExecutivesController@getListView',
		 'permission' => 'Executives List per company']);
		
		Route::get('create/{company_id}',
		['as'	=>	'admin-executives-create-get',
		 'uses'	=>	'ExecutivesController@getCreateView',
		 'permission' => 'Executives Create']);

		Route::post('create/{company_id}',
		['as'	=>	'admin-executives-create-post',
		 'uses'	=>	'ExecutivesController@postCreateView',
		 'permission' => 'Executives Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-executives-edit-get',
		 'uses'	=>	'ExecutivesController@getEditView',
		 'permission' => 'Executives Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-executives-edit-post',
		 'uses'	=>	'ExecutivesController@postEditView',
		 'permission' => 'Executives Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-executives-delete-post',
		 'uses'	=>	'ExecutivesController@postDeleteView',
		 'permission' => 'Executives Delete']);

		Route::post('delete-multiple/',
		['as'	=>	'admin-executives-delete-multiple-post',
		 'uses'	=>	'ExecutivesController@postDeleteMultipleView',
		 'permission' => 'Executives Delete']);
});
