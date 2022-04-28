<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Executives\Api'], function() {
    
    Route::get('company/executives/list/{slug}/{tab_id}', [
        'uses'  =>'ApiExecutivesController@getExecutivesList'
    ]);
    Route::get('company/executives-tabs/list', [
        'uses'  =>'ApiExecutivesController@getExecutivesTabList'
    ]);
});