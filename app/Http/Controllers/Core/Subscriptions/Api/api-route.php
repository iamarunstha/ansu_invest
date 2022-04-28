<?php
Route::group(['prefix'	=>	'v1', 'namespace' => '\App\Http\Controllers\Core\Subscriptions\Api'], function() {

    Route::post('subscription-request/', [
        'uses'  => 'ApiSubscriptionController@postSubscriptionRequest',
		// 'middleware'	=>	'jwt'
    ]);

    Route::get('subscription-list/', [
    	'uses'	=> 'ApiSubscriptionController@getSubscriptionList',
		  'middleware'	=>	'jwt'
    ]);

    Route::get('subscription-plans/', [
      'uses'  => 'ApiSubscriptionController@getSubscriptionPlans',
    ]);
});
