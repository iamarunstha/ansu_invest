<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Search\Api'], function() {
    Route::get('search', [
        'uses'  =>'ApiSearchController@getSearch'
    ]);
});