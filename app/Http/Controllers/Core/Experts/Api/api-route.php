<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Experts\Api'], function() {
    Route::get('experts/{id}', [
        'uses'  =>'ApiExpertsController@getExperts'
    ]);

    Route::get('experts-list', [
        'uses'  =>'ApiExpertsController@getExpertsList'
    ]);

    Route::get('experts-show/{no_of_items}', [
        'uses'  =>'ApiExpertsController@getExpertsShow'
    ]);

    Route::get('featured-experts-show/{no_of_items}', [
        'uses'  =>'ApiExpertsController@getFeaturedExpertsShow'
    ]);

    Route::get('related-experts/{id?}', [
        'uses'  =>  'ApiExpertsController@getRelatedExperts'
    ]);
});