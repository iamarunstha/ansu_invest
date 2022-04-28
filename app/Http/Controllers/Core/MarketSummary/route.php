<?php

Route::group(['prefix'	=>	'admin/market-summary', 'route_group' => 'Market Summary', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\MarketSummary'], function() {
	Route::get('upload-market-summary', [
		'as'	=>	'upload-market-summary-get',
		'uses'	=>	'MarketSummaryController@getUploadNepse',
		'permission' => 'Market Summary Upload'
	]);

	Route::get('download-market-summary', [
		'as'	=>	'donwload-market-summary-get',
		'uses'	=>	'MarketSummaryController@getDownloadUpperNepse',
		'permission' => 'Market Summary Download'
	]);

	Route::post('upload-market-summary', [
		'as'	=>	'upload-market-summary-post',
		'uses'	=>	'MarketSummaryController@postUploadNepse',
		'permission' => 'Market Summary Upload'
	]);
});
