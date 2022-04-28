<?php

	Route::group(['prefix'	=>	'admin/valuation', 'route_group'=>'Sector', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Valuation'], function() {

		Route::get('/headings/{sector_id}',
		['as'	=>	'admin-valuation-headings-get',
		 'uses'	=>	'ValuationController@getValuationHeadingsView',
		 'permission' => 'List Valuation Headings']); 

		Route::post('/headings/{sector_id}',
		['as'	=>	'admin-valuation-headings-post',
		 'uses'	=>	'ValuationController@postValuationHeadingsView',
		 'permission' => 'Update Valuation Headings']);

		Route::post('/headings-create/{sector_id}',
		['as' => 'admin-valuation-headings-create-post',
		 'uses' => 'ValuationController@postValuationHeadingsCreateView',
		 'permission' => 'Create Valuation Heading']);

		Route::post('/headings-delete/{heading_id}',
		['as' => 'admin-valuation-headings-delete-post',
		 'uses' => 'ValuationController@postValuationHeadingsDeleteView',
		 'permission' => 'Delete Valuation Heading']);

	});

	Route::group(['prefix'	=>	'admin/valuation', 'route_group'=>'Company', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Valuation'], function() {

		Route::get('upload-valuation/{company_id}',[
			'as'	=>	'admin-company-upload-valuation-get',
			'uses'	=>	'ValuationController@getUploadValuation',
		 	'permission' => 'Upload Company\'s Valuation'
		]);

		Route::post('upload-valuation/{company_id}',[
			'as'	=>	'admin-company-upload-valuation-post',
			'uses'	=>	'ValuationController@postUploadValuation',
		 	'permission' => 'Upload Company\'s Valuation'
		]);

		Route::get('download-valuation-upload-excel/{company_id}',[
			'as'	=>	'admin-company-valuation-upload-excel',
			'uses'	=>	'ValuationController@downloadUploadValuation',
		 	'permission' => 'Download Company\'s Valuation'
		]);
	
	});



