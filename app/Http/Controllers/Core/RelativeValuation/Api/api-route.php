<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\RelativeValuation\Api'], function() {
    
    Route::get('company/relative-valuation/{slug}', [
        'uses' => 'ApiRelativeValuationController@getRelativeValuation'
    ]);
});
