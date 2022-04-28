<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\UpperIndex\Api'], function() {

    Route::get('upper-index-list/{date?}', [
        'uses'  =>  'ApiUpperIndexController@getUpperIndexList'
    ]);

    Route::get('nepse-index/{date?}', [
        'uses' => 'ApiUpperIndexController@getNepseIndex'
    ]);
});
