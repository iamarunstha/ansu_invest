<?php

Route::group(['prefix'	=>	'admin/upper-index', 'route_group'=>'NEPSE Index', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\UpperIndex'], function() {
	Route::get('upload-nepse', [
		'as'	=>	'upload-nepse-get',
		'uses'	=>	'UpperIndexController@getUploadNepse',
		'permission' => 'Go to Nepse Index page'
	]);

	Route::get('download-upper-nepse', [
		'as'	=>	'donwload-upper-nepse-get',
		'uses'	=>	'UpperIndexController@getDownloadUpperNepse',
		'permission' => 'Nepse Index download'
	]);

	Route::post('upload-nepse', [
		'as'	=>	'upload-nepse-post',
		'uses'	=>	'UpperIndexController@postUploadNepse',
		'permission' => 'Nepse Index Upload'
	]);
});