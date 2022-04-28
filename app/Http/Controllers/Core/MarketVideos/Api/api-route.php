<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\MarketVideos\Api'], function() {
    Route::get('market-videos/{id}', [
        'uses'  =>'ApiMarketVideosController@getMarketVideos'
    ]);

    Route::get('market-videos-list', [
        'uses'  =>'ApiMarketVideosController@getMarketVideosList'
    ]);

    Route::get('market-videos-show/{no_of_items}', [
        'uses'  =>'ApiMarketVideosController@getMarketVideosShow'
    ]);

    Route::get('featured-market-videos/{no_of_items}', [
        'uses'  =>'ApiMarketVideosController@getFeaturedMarketVideos'
    ]);

    Route::get('related-market-videos/{id?}', [
        'uses'  =>  'ApiMarketVideosController@getRelatedMarketVideos'
    ]);
});