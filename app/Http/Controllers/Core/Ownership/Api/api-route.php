<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Ownership\Api'], function() {
    
    Route::get('company/ownership/list/{slug}/{tab_id}', [
        'uses'  =>'ApiOwnershipController@getOwnershipList'
    ]);
    Route::get('company/ownership-tabs/list', [
        'uses'  =>'ApiOwnershipController@getOwnershipTabList'
    ]);
});