<?php
	Route::group(['prefix'	=>	'admin/agm-sgm', 'route_group'=>'AGM-SGM', 'middleware'=>'can:isAuthorized','namespace' => '\App\Http\Controllers\Core\AgmSgm'], function() {

		Route::get('list',
		['as'	=>	'admin-agm-sgm-list-get',
		 'uses'	=>	'AgmSgmController@getListView',
		 'permission' => 'AGM SGM List']);

		Route::get('create',
		['as'	=>	'admin-agm-sgm-create-get',
		 'uses'	=>	'AgmSgmController@getCreateView',
		 'permission' => 'AGM SGM Create']);

		Route::post('create',
		['as'	=>	'admin-agm-sgm-create-post',
		 'uses'	=>	'AgmSgmController@postCreateView',
		 'permission' => 'AGM SGM Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-agm-sgm-edit-get',
		 'uses'	=>	'AgmSgmController@getEditView',
		 'permission' => 'AGM SGM Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-agm-sgm-edit-post',
		 'uses'	=>	'AgmSgmController@postEditView',
		 'permission' => 'AGM SGM Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-agm-sgm-delete-post',
		 'uses'	=>	'AgmSgmController@postDeleteView',
		 'permission' => 'AGM SGM Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-agm-sgm-delete-multiple-post',
		 'uses'	=>	'AgmSgmController@postDeleteMultipleView',
		 'permission' => 'AGM SGM Delete']);

});