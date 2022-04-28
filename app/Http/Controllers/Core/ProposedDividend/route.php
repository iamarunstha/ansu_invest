<?php

Route::group(['prefix'	=>	'admin/proposed-dividend', 'route_group'=>'Proposed Dividend', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\ProposedDividend'], function() {

		Route::get('list',
		['as'	=>	'admin-proposed-dividend-list-get',
		 'uses'	=>	'ProposedDividendController@getListView',
		 'permission' => 'Proposed Dividend List']);

		Route::get('edit/{id}',
		['as'	=>	'admin-proposed-dividend-edit-get',
		 'uses' =>	'ProposedDividendController@getEditView',
		 'permission' => 'Proposed Dividend Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-proposed-dividend-edit-post',
		 'uses' =>	'ProposedDividendController@postEditView',
		 'permission' => 'Proposed Dividend Edit']);

		Route::post('create',
		['as'	=>	'admin-proposed-dividend-create-post',
		 'uses'	=>	'ProposedDividendController@postCreateView',
		 'permission' => 'Proposed Dividend Create']);

		Route::post('update',
		['as'	=>	'admin-proposed-dividend-update-post',
		 'uses'	=>	'ProposedDividendController@postUpdateView',
		 'permission' => 'Proposed Dividend Update']);

		Route::post('delete/{dividend_id}',
		['as'	=>	'admin-proposed-dividend-delete-post',
		 'uses'	=>	'ProposedDividendController@postDeleteView',
		 'permission' => 'Proposed Dividend Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-proposed-dividend-delete-multiple-post',
		 'uses'	=>	'ProposedDividendController@postDeleteMultipleView',
		 'permission' => 'Proposed Dividend Delete']);

});