<?php

Route::group(['prefix'	=>	'admin/stock', 'route_group'=>'Stock', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Stock'], function() {

	Route::get('list',
	['as'	=>	'admin-stock-list-get',
	 'uses'	=>	'StockController@getListView',
	 'permission' => 'Stock List']);

	Route::get('create',
	['as'	=>	'admin-stock-create-get',
	 'uses'	=>	'StockController@getCreateView',
	 'permission' => 'Stock Create']);

	Route::post('create',
	['as'	=>	'admin-stock-create-post',
	 'uses'	=>	'StockController@postCreateView',
	 'permission' => 'Stock Create']);

	Route::get('edit/{id}',
	['as'	=>	'admin-stock-edit-get',
	 'uses'	=>	'StockController@getEditView',
	 'permission' => 'Stock Edit']);

	Route::post('edit/{id}',
	['as'	=>	'admin-stock-edit-post',
	 'uses'	=>	'StockController@postEditView',
	 'permission' => 'Stock Edit']);

	Route::post('delete/{id}',
	['as'	=>	'admin-stock-delete-post',
	 'uses'	=>	'StockController@postDeleteView',
	 'permission' => 'Stock Delete']);

	Route::post('delete-multiple',
	['as'	=>	'admin-stock-delete-multiple-post',
	 'uses' =>	'StockController@postDeleteMultipleView',
	 'permission' => 'Stock Delete']);
});