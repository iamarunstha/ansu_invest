<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Company\Api'], function() {
    Route::get('company-valuation/{no_of_company}', [
        'uses'  =>'ApiCompanyController@getCompanyValuation'
    ]);

    Route::get('company/{slug}', [
        'uses'  =>'ApiCompanyController@getCompanyFromSlug',
    ]);

    Route::get('company-list/{slug?}', [
        'uses'  =>  'ApiCompanyController@getCompanyList'
    ]);

    Route::get('company/quotes/{slug}', [
        'uses'  =>  'ApiCompanyController@getQuotes'
    ]);

    Route::get('company/quotes/graph/{slug}',[
        'uses'  =>  'ApiCompanyController@getQuotesGraph'
    ]);

    Route::get('company/financials/child-tabs/{slug}/{tab_id}',[
        'uses'  =>  'ApiCompanyController@getFinancialsChildTabs'
    ]);

    Route::get('company/financials/{slug}', [
        'uses'  =>  'ApiCompanyController@getFinancials'
    ]);

    Route::get('company/financials-table-data/{slug}',[
        'uses'  =>  'ApiCompanyController@getFinancialsTableData'
    ]);

    Route::get('company/financials-table-data-full/{slug}/{tab_id}',[
        'uses'  =>  'ApiCompanyController@getFinancialsTableDataFull'
    ]);

    Route::get('company/financials/statements/tabs/{slug}',[
        'uses'  =>  'ApiCompanyController@getFinancialsTabs'
    ]);

    Route::get('company/financials/statements/history-tabs/{slug}',[
        'uses'  =>  'ApiCompanyController@getHistoricalFinancialsTabs'
    ]);

    Route::get('company/valuation-table-data-full/{slug}',[
        'uses'  =>  'ApiCompanyController@getValuationTableDataFull'
    ]);

    Route::get('company/performance-table-data-full/{slug}',[
        'uses'  =>  'ApiCompanyController@getPerformanceTableDataFull'
    ]);

    Route::get('company/dividend-type/{slug}',[
        'uses'  =>  'ApiCompanyController@getDividendType'
    ]);

    Route::get('company/right-share-table-data-full/{slug}', [
        'uses'  =>  'ApiCompanyController@getDividendTableDataFull'
    ]);

    Route::get('company/dividend-table-data-full/{slug}',[
        'uses'  =>  'ApiCompanyController@getDividendTableDataFull'
    ]);

    Route::get('company/fair-value/{slug}', [
        'uses'  =>  'ApiCompanyController@getFairValue'
    ]);

    Route::get('proposed-dividend-table/', [
        'uses'  =>  'ApiCompanyController@getProposedDividendTable'
    ]);

    Route::get('proposed-dividend-table-full/', [
        'uses'  =>  'ApiCompanyController@getProposedDividendTableFull'
    ]);

    Route::get('agm-sgm-table/', [
        'uses'  =>  'ApiCompanyController@getAgmSgmTable'
    ]);

    Route::get('agm-sgm-table-full/', [
        'uses'  =>  'ApiCompanyController@getAgmSgmTableFull'
    ]);

    Route::get('company/experts-list/{slug}', [
        'uses'  =>  'ApiCompanyController@getCompanyExpert'
    ]);

    Route::post('login/', [
        'uses'  =>  'ApiCompanyController@login'
    ]);

    Route::get('check-company-has-historical-tabs/{slug}', [
        'uses'  => 'ApiCompanyController@checkCompanyHasHistoricalTabs'
    ]);

    Route::get('check-sector-has-historical-tabs/{sector_id}', [
        'uses'  => 'ApiCompanyController@checkSectorHasHistoricalTabs'
    ]);
});