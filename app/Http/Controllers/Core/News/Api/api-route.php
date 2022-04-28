<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\News\Api'], function() {
    Route::get('news/{id}', [
        'uses'  =>  'ApiNewsController@getNews'
    ]);

    Route::get('company/news-list/{slug}', [
        'uses'  =>  'ApiNewsController@getCompanyNewsList'
    ]);

    Route::get('news-list', [
        'uses'  =>'ApiNewsController@getNewsList'
    ]);

    Route::get('top-news', [
        'as'	=>	'frontend-top-news',
        'uses'	=>	'ApiNewsController@getTopNews'
    ]);

    Route::get('most-read', [
        'as'	=>	'frontend-most-read',
        'uses'	=>	'ApiNewsController@getMostRead'
    ]);

    Route::get('latest-news', [
        'as'	=>	'frontend-latest-news',
        'uses'	=>	'ApiNewsController@getLatestNews'
    ]);

    Route::get('newsboard', [
        'as'	=>	'frontend-newsboard',
        'uses'	=>	'ApiNewsController@getNewsBoard'
    ]);

    Route::get('related-news/{id?}', [
        'uses'  =>  'ApiNewsController@getRelatedNews'
    ]);

    Route::get('news-reports/', [
        'uses'  =>  'ApiNewsController@getNewsReports'
    ]);

    Route::get('company/report-list/{slug}', [
        'uses'  =>  'ApiNewsController@getCompanyReportList'
    ]);
});