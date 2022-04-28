<?php

Route::group(['prefix'	=>	'admin/suggested-reads', 'route_group'=>'Suggested Reads', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\SuggestedReads'], function() {
	Route::get('suggested-reads', [
		'as'	=>	'suggested-reads-get',
		'uses'	=>	'SuggestedReadsController@getSuggestedReads',
		'permission' => 'View Suggested Reads'
	]);

	Route::post('suggested-reads/update', [
		'as'	=>	'suggested-reads-update-post',
		'uses'	=>	'SuggestedReadsController@postSuggestedReads',
		'permission' => 'Update Suggested Reads'
	]);

	Route::post('suggested-reads/remove/{id}', [
		'as'	=>	'suggested-reads-delete-post',
		'uses'	=>	'SuggestedReadsController@postDeleteSuggestedReads',
		'permission' => 'Remove Suggested Reads'
	]);
});
