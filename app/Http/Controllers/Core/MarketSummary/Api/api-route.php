<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\MarketSummary\Api'], function() {

    Route::get('market-summary-list/{date?}', [
        'uses'  =>  'ApiMarketSummaryController@getMarketSummaryList'
    ]);

});