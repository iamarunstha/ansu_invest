<?php

	Route::group(['prefix'	=>	'admin/relative-valuation', 'route_group'=>'Absolute Valuation', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\RelativeValuation'], function() {
		
		Route::get('list',
		['as'	=>	'admin-relative-valuation-list-get',
		 'uses'	=>	'RelativeValuationController@getListView',
		 'permission' => 'Absolute Valuation List']);

		Route::get('create/{company_id}',
		['as'	=>	'admin-relative-valuation-create-get',
		 'uses'	=>	'RelativeValuationController@getCreateView',
		 'permission' => 'Absolute Valuation Create']);

		Route::post('create/{company_id}',
		['as'	=>	'admin-relative-valuation-create-post',
		 'uses'	=>	'RelativeValuationController@postCreateView',
		 'permission' => 'Absolute Valuation Create']);

		Route::get('edit',
		['as'	=>	'admin-relative-valuation-edit-get',
		 'uses'	=>	'RelativeValuationController@getEditView',
		 'permission' => 'Absolute Valuation Edit']);

		Route::post('edit',
		['as'	=>	'admin-relative-valuation-edit-post',
		 'uses'	=>	'RelativeValuationController@postEditView',
		 'permission' => 'Absolute Valuation Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-relative-valuation-delete-post',
		 'uses'	=>	'RelativeValuationController@postDeleteView',
		 'permission' => 'Absolute Valuation Delete']);

		Route::post('delete-multiple/',
		['as'	=>	'admin-relative-valuation-delete-multiple-post',
		 'uses'	=>	'RelativeValuationController@postDeleteMultipleView',
		 'permission' => 'Absolute Valuation Delete']);

	});
