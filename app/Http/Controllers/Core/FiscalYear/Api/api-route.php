<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\FiscalYear\Api'], function() {
	Route::get('fiscal-year-list', [
		'uses'	=> 'ApiFiscalYearController@getFiscalYearList'
	]);
});
