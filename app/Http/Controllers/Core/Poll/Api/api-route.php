<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Poll\Api'], function() {
   
    Route::get('poll/company/{slug?}', [
        'uses'  =>	'ApiPollController@getPollOfCompany'
    ]);

    Route::get('poll/active-poll', [
        'uses'  =>	'ApiPollController@getActivePoll'
    ]);

    Route::post('update-poll/{company_id}', [
    	'uses'	=>	'ApiPollController@updatePollCompany'
    ]);
});