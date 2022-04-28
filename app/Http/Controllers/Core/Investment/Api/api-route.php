<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Investment\Api'], function() {
    
    Route::get('investment-tabs-list', [
        'uses'  =>'ApiInvestmentController@getInvestmentTabsList'
    ]);

    Route::get('investment-existing-issues-table/{id}', [
        'uses'  =>  'ApiInvestmentController@getInvestmentExistingIssuesTable'
    ]);

    Route::get('investment-existing-issues-table-full/{id}', [
        'uses'  =>  'ApiInvestmentController@getInvestmentExistingIssuesTableFull'
    ]);
});