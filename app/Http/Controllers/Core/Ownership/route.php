<?php

Route::group(['prefix'	=>	'admin/ownership', 'route_group'=>'Ownership', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Ownership'], function() {

	Route::get('company/list/{tab_id}',
	['as'	=>  'admin-ownership-company-list-get',
	 'uses'	=>	'OwnershipController@getCompanyListView',
	 'permission' => 'Company List for ownership']);

	Route::get('company/list/{company_id}/{tab_id}',
	['as'	=>	'admin-ownership-list-get',
	 'uses' =>	'OwnershipController@getListView',
	 'permission' => 'Ownership List']);

	Route::get('list-tab/',
	['as'	=>  'admin-ownership-tabs-list-get',
	 'uses'	=>	'OwnershipController@getTabsListView',
	 'permission' => 'Ownership Tab List']);

	Route::post('create-tab/',
	['as'	=>	'admin-ownership-tabs-create-post',
	 'uses' =>	'OwnershipController@postTabsCreateView' ,
	 'permission' => 'Ownership Tab Create']);

	Route::post('update-tab/{tab_id}',
	['as'	=>	'admin-ownership-tabs-update-post',
	 'uses' =>	'OwnershipController@postTabsUpdateView',
	 'permission' => 'Ownership Tab Udpate']);

	Route::post('delete-tab/{tab_id}',
	['as'	=>	'admin-ownership-tabs-delete-post',
	 'uses' =>	'OwnershipController@postTabsDeleteView',
	 'permission' => 'Ownership Tab Delete']);

	Route::get('create/{company_id}/{tab_id}',
	['as'	=>	'admin-ownership-create-get',
	 'uses'	=>	'OwnershipController@getCreateView',
	 'permission' => 'Ownership Create']);

	Route::post('create/{company_id}/{tab_id}',
	['as'	=>	'admin-ownership-create-post',
	 'uses'	=>	'OwnershipController@postCreateView',
	 'permission' => 'Ownership Create ']);

	Route::get('edit/{company_id}/{tab_id}/{name_id}',
	['as'	=>	'admin-ownership-edit-get',
	 'uses' =>	'OwnershipController@getEditView',
	 'permission' => 'Ownership Edit']);

	Route::post('edit/{company_id}/{tab_id}/{name_id}',
	['as'	=>	'admin-ownership-edit-post',
	 'uses' =>	'OwnershipController@postEditView',
	 'permission' => 'Ownership Edit']);

	Route::post('delete/{company_id}/{tab_id}/{name_id}',
	['as'	=>	'admin-ownership-delete-post',
	 'uses'	=>	'OwnershipController@postDeleteView',
	 'permission' => 'Ownership Delete']);

	Route::post('delete-multiple',
	['as'	=>	'admin-ownership-delete-multiple-post',
	 'uses'	=>	'OwnershipController@postDeleteMultipleView',
	 'permission' => 'Ownership Delete']);

	Route::get('list-column',
	['as'	=>	'admin-ownership-columns-list-get',
	 'uses' =>  'OwnershipController@getColumnListView',
	 'permission' => 'Ownership Column List']);

	Route::post('create-column',
	['as'	=>	'admin-ownership-columns-create-post',
	 'uses' =>  'OwnershipController@postColumnCreateView',
	 'permission' => 'Ownership Create Column']);

	Route::post('edit-column/{column_id}',
	['as'	=>	'admin-ownership-columns-update-post',
	 'uses' =>  'OwnershipController@postColumnsEditView',
	 'permission' => 'Ownership Edit Column']);

	Route::post('delete-column/{column_id}',
	['as'	=>	'admin-ownership-columns-delete-post',
	 'uses' =>  'OwnershipController@postColumnDeleteView',
	 'permission' => 'Ownership Delete Column']);
});
