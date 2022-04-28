<?php

Route::group(['prefix'	=>	'v1/static-page', 'namespace' => '\App\Http\Controllers\Core\StaticPage\Api'], function() {
    Route::get('view/{id}', [
        'uses'  =>'ApiStaticPageController@getStaticPage'
    ]);
});