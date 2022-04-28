<?php

Route::group(['prefix'	=>	'admin/subscription', 'route_group'=>'Subscription', 'middleware' => 'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Subscriptions'], function() {

	Route::get('/plans/list', 
	['as'	=>	'admin-subscripton-plans-get',
	 'uses'	=>	'SubscriptionController@getPlansListView',
	 'permission' => 'Subscriptions Plans List']);

	Route::post('/plans/add',
	['as'	=>	'admin-subscripton-plans-add-post',
	 'uses'	=>	'SubscriptionController@postPlansAddView',
	 'permission' => 'Subscriptions Plans Add']);

	Route::post('/plans/update',
	['as'	=>	'admin-subscripton-plans-update-post',
	 'uses'	=>	'SubscriptionController@postPlansUpdateView',
	 'permission' => 'Subscriptions Plans Edit']);

	Route::post('/plans/delete/{id}',
	['as'	=>	'admin-subscription-plans-delete-post',
	 'uses'	=>	'SubscriptionController@postPlansDeleteView',
	 'permission' => 'Subscriptions Plans Delete']);

	Route::get('/requests/list',
	['as'	=>	'admin-subscripton-requests-get',
	 'uses'	=>	'SubscriptionController@getListView',
	 'permission' => 'Subscriptions Request List']);

	Route::post('/approve/{request_id}',
	['as'	=>	'admin-subscripton-requests-approve-post',
	 'uses'	=>	'SubscriptionController@postApprove',
	 'permission' => 'Subscriptions Approve']);

	Route::post('/reject/{request_id}',
	['as'	=>	'admin-subscripton-requests-reject-post',
	 'uses'	=>	'SubscriptionController@postReject',
	 'permission' => 'Subscriptions Reject']);

	Route::get('/list',
	['as'	=>	'admin-subscription-list-get',
	 'uses'	=>	'SubscriptionController@getSubscriptionListView',
	 'permission' => 'Subscriptions List']);	

	Route::get('/rejected/list',
	['as'	=>	'admin-subscription-rejected-list-get',
	 'uses'	=>	'SubscriptionController@getSubscriptionRejectedListView',
	 'permission' => 'Subscriptions Rejected List']);

	Route::post('/requests/delete/{request_id}',
	['as'	=> 	'admin-subscripton-requests-delete-post',
	 'uses' =>	'SubscriptionController@postDeleteView',
	 'permission' => 'Subscriptions Delete']);

	Route::get('/client-history/{client_id}',
	['as'	=>	'admin-client-history-get',
	'uses'	=>	'SubscriptionController@getClientHistoryView',
	'permission' => 'Subscriptions View Client History ']);
});
