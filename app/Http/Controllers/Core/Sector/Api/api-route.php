<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Sector\Api'], function() {

    Route::get('sector-list/', [
        'uses'  =>  'ApiSectorController@getSectorList'
    ]);

});