<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Footer\Api'], function() {
    Route::get('footer/', [
        'uses'  =>'ApiFooterController@getFooter'
    ]);
});
