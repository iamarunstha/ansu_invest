<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Stock\Api'], function() {
    Route::get('company/stock-analysis/{slug}', [
        'uses'  =>'ApiStockController@getStock'
    ]);

    Route::get('company/stock-analysis-full/{slug}', [
        'uses'  =>'ApiStockController@getStockFull'
    ]);
});