<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Graph\Api'], function() {
    Route::get('graph/balance-sheet', [
        'uses'  =>'ApiGraphController@getBalanceSheetGraph'
    ]);
    Route::get('graph/valuation', [
    	'uses'	=>'ApiGraphController@getValuationGraph'
    ]);
});
