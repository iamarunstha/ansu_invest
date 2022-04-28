<?php

Route::group(['prefix'	=>	'admin/notice', 'route_group'=>'Notice', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Notice'], function() {

		Route::get('list',
		['as'	=>	'admin-notice-list-get',
		 'uses'	=>	'NoticeController@getListView',
		 'permission' => 'Notice List']);

		Route::get('edit/{id}',
		['as'	=>	'admin-notice-edit-get',
		 'uses' =>	'NoticeController@getEditView',
		 'permission' => 'Notice Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-notice-edit-post',
		 'uses' =>	'NoticeController@postEditView',
		 'permission' => 'Notice Edit']);

		Route::post('create',
		['as'	=>	'admin-notice-create-post',
		 'uses'	=>	'NoticeController@postCreateView',
		 'permission' => 'Notice Create']);

		Route::post('delete/{id}',
		['as'	=>	'admin-notice-delete-post',
		 'uses'	=>	'NoticeController@postDeleteView',
		 'permission' => 'Notice Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-notice-delete-multiple-post',
		 'uses'	=>	'NoticeController@postDeleteMultipleView',
		 'permission' => 'Notice Delete']);

});