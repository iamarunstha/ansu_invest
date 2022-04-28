<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\ClientUser\Api', 'middleware' => 'auth:api'], function() {
    Route::get('client-detail/', [
        'uses'  =>'ApiClientUserController@getClientDetail'
    ]);

    Route::post('client-detail/update', [
        'uses'  => 'ApiClientUserController@postClientDetail'
    ]);
});
