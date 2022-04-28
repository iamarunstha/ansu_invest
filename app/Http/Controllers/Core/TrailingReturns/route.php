<?php

Route::group(['prefix'	=>	'admin/trailing-returns', 'route_group'=>'Trailing Returns', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\TrailingReturns'], function() {

	Route::get('upload/',
	['as' => 'admin-trailing-returns-upload-get',
	 'uses' => 'TrailingReturnsController@getTrailingReturnsUploadView',
	 'permission' => 'Trailing Returns Upload ']);

	Route::post('upload/',
	['as' => 'admin-trailing-returns-upload-post',
	 'uses' => 'TrailingReturnsController@postTrailingReturnsUploadView',
	 'permission' => 'Trailing Returns Upload ']);

	Route::get('download/',
	['as' => 'admin-trailing-returns-download-excel-get',
	 'uses' => 'TrailingReturnsController@getTrailingReturnsDownloadExcel',
	 'permission' => 'Trailing Returns Download ']);

	// Route::get('types/',
	// ['as' => 'admin-trailing-returns-types-get',
	// 'uses'  => 'TrailingReturnsController@getTypesListView',
	// 'permission' => 'Trailing Returns Types ']);
});