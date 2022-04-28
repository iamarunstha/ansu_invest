<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\SuggestedReads\Api'], function() {
    Route::get('suggested-reads/main', [
    	'uses'	=> 'ApiSuggestedReadsController@getSuggestedReads',
    ]);
    Route::get('suggested-reads/sidebar', [
    	'uses'	=> 'ApiSuggestedReadsController@getSuggestedReadsSidebar',
    ]);
});
