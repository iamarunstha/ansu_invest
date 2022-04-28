<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\TrailingReturns\Api'], function() {
    Route::get('company/trailing-returns/tab-list', [
        'uses'  =>	'ApiTrailingReturnsController@getTabList'
    ]);
    Route::get('company/trailing-returns/{slug}/{tab_id}', [
    	'uses'	=>	'ApiTrailingReturnsController@getTrailingReturns'
    ]);
});