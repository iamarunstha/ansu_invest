<?php
Route::group(['prefix' => 'admin/poll', 'route_group' => 'Company', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Poll'], function() {
	Route::post('set-poll/{company_id}', [
		'as'	=>	'adimn-poll-set-poll-post',
		'uses'	=>	'PollController@postSetPoll',
		'permission' => 'Set Poll'
	]);
});
