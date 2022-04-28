<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Recommendations\Api'], function() {
    Route::get('recommendations/{id}', [
        'uses'  =>'ApiRecommendationsController@getRecommendations'
    ]);

    Route::get('recommendations-show/{no_of_items}', [
    	'uses'	=>'ApiRecommendationsController@getRecommendationsShow'
    ]);

    Route::get('top-recommendations/{no_of_items}', [
    	'uses'	=>'ApiRecommendationsController@getTopRecommendations'
    ]);

    Route::get('recommendations-list', [
        'uses'  =>'ApiRecommendationsController@getRecommendationsList'
    ]);

    Route::get('recommendations', [
        'uses'  =>'ApiRecommendationsController@getRecommendationsList'
    ]);

    Route::get('related-recommendations/{id?}', [
         'uses'  =>'ApiRecommendationsController@getRelatedRecommendations'
    ]);

});