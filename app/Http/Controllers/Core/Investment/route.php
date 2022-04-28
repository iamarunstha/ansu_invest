<?php

Route::group(['prefix'	=>	'admin/investment','route_group'=>'Investment', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Investment'], function() {

		Route::get('list-tab/',
		['as'	=>  'admin-investment-tabs-list-get',
		 'uses'	=>	'InvestmentController@getTabsListView',
		 'permission' => 'Investment Tabs List']);

		Route::get('list/{tab_id}',
		['as'	=>	'admin-investment-list-get',
		 'uses'	=>	'InvestmentController@getListView',
		 'permission' => 'Investment List per tab']);

		Route::get('create/{tab_id}',
		['as'	=>	'admin-investment-create-get',
		 'uses'	=>	'InvestmentController@getCreateView',
		 'permission' => 'Investment Create']);

		Route::post('create/{tab_id}',
		['as'	=>	'admin-investment-create-post',
		 'uses'	=>	'InvestmentController@postCreateView',
		 'permission' => 'Investment Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-investment-edit-get',
		 'uses' =>	'InvestmentController@getEditView',
		 'permission' => 'Investment Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-investment-edit-post',
		 'uses' =>	'InvestmentController@postEditView',
		 'permission' => 'Investment Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-investment-delete-post',
		 'uses'	=>	'InvestmentController@postDeleteView',
		 'permission' => 'Investment Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-investment-delete-multiple-post',
		 'uses'	=>	'InvestmentController@postDeleteMultipleView',
		 'permission' => 'Investment Delete']);

});
