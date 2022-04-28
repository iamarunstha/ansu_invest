<?php

Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Notice\Api'], function() {
   
    Route::get('notices/{no_of_notice?}', [
        'uses'  =>'ApiNoticeController@getNoticeList'
    ]);

    Route::get('notice/{id}', [
    	'uses'	=>'ApiNoticeController@getNotice'
    ]);

    Route::get('company/notices/{slug}', [
        'uses'  =>  'ApiNoticeController@getCompanyNotices'
    ]);
});