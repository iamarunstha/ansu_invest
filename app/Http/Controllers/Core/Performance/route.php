<?php

	Route::group(['prefix'	=>	'admin/performance', 'route_group'=>'Sector', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Performance'], function() {

		Route::get('/headings/{sector_id}',
		['as'	=>	'admin-performance-headings-get',
		 'uses'	=>	'PerformanceController@getPerformanceHeadingsView',
		 'permission' => 'List Operating Performance Headings']); 

		Route::post('/headings/{sector_id}',
		['as'	=>	'admin-performance-headings-post',
		 'uses'	=>	'PerformanceController@postPerformanceHeadingsView',
		 'permission' => 'Update Operating Performance Headings']);

		Route::post('/headings-create/{sector_id}',
		['as' => 'admin-performance-headings-create-post',
		 'uses'=> 'PerformanceController@postPerformanceHeadingsCreateView',
		 'permission' => 'Create Operating Performance Headings']);

		Route::post('/headings-delete/{heading_id}',
		['as' => 'admin-performance-headings-delete-post',
		 'uses'=> 'PerformanceController@postPerformanceHeadingsDeleteView',
		 'permission' => 'Delete Operating Performance Headings']);

	});


	Route::group(['prefix'	=>	'admin/performance', 'route_group'=>'Company', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Performance'], function() {

		Route::get('upload-performance/{company_id}',[
			'as'	=>	'admin-company-upload-performance-get',
			'uses'	=>	'PerformanceController@getUploadPerformance',
			'permission' => 'Upload Company\'s Operating Performance']);

		Route::post('upload-performance/{company_id}',[
			'as'	=>	'admin-company-upload-performance-post',
			'uses'	=>	'PerformanceController@postUploadPerformance',
			'permission' => 'Upload Company\'s Operating Performance']);

		Route::get('download-performance-upload-excel/{company_id}',[
			'as'	=>	'admin-company-performance-upload-excel',
			'uses'	=>	'PerformanceController@downloadUploadPerformance',
			'permission' => 'Download Company\'s Operating Performance']);
	
	});