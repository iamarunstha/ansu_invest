<?php
	Route::group(['prefix'	=>	'admin/balance-sheet','route_group'=>'Sector', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\BalanceSheet'], function() {

		Route::get('/sector-list',
		['as'	=>	'admin-balance-sheet-sector-list-get',
		 'uses'	=>	'BalanceSheetController@getListView',
		 'permission' => 'Sector List']);

		Route::post('/sector-create',
		['as'	=>	'admin-balance-sheet-sector-create-post',
		 'uses' =>	'BalanceSheetController@postBalanceSheetSectorCreateView',
		 'permission' => 'Sector Create']);

		Route::post('/sector-delete/{sector_id}',
		['as'	=>	'admin-balance-sheet-sector-delete-post',
		 'uses' =>	'BalanceSheetController@postBalanceSheetSectorDeleteView',
		 'permission' => 'Sector Delete']);

		Route::post('/sector-update/{sector_id}',
		['as'   => 'admin-balance-sheet-sector-update-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetSectorUpdateView',
		 'permission' => 'Sector Update']);

		Route::get('/headings/{sector_id}/{tab_id}',
		['as'	=>	'admin-balance-sheet-headings-get',
		 'uses'	=>	'BalanceSheetController@getBalanceSheetHeadingsView',
		 'permission' => 'Sector Headings View']); 

		Route::post('/headings/{sector_id}/{tab_id}',
		['as'	=>	'admin-balance-sheet-headings-post',
		 'uses'	=>	'BalanceSheetController@postBalanceSheetHeadingsView',
		 'permission' => 'Sector Headings Update']);

		Route::post('/headings-create/{sector_id}/{tab_id}',
		['as' => 'admin-balance-sheet-headings-create-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetHeadingsCreateView',
		 'permission' => 'Sector Headings Create']);

		Route::post('/headings-delete/{heading_id}',
		['as' => 'admin-balance-sheet-headings-delete-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetHeadingsDeleteView',
		 'permission' => 'Sector Heading Delete']);

		Route::get('/tabs-list/', 
		['as' => 'admin-balance-sheet-tabs-list-get',
		 'uses'	=> 'BalanceSheetController@getTabsListView',
		 'permission' => 'Tabs List']);

		Route::post('/tabs-create/',
		['as' => 'admin-balance-sheet-tabs-create-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetTabsCreateView',
		 'permission' => 'Tabs Create']);

		Route::post('/tabs-add/{sector_id}',
		['as' => 'admin-balance-sheet-tabs-add-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetTabsAddView',
		 'permission' => 'Tabs Add to Sector']);

		Route::post('/tabs-update/{tab_id}', 
		['as' => 'admin-balance-sheet-tabs-update-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetTabsUpdateView',
		 'permission' => 'Tabs Update']);

		Route::post('/sector-tabs-delete/{sector_id}/{tab_id}',
		['as' => 'admin-balance-sheet-sector-tabs-delete-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetSectorTabsDeleteView',
		 'permission' => 'Tabs Remove from Sector']);

		Route::post('/tabs-delete/{tab_id}',
		['as' => 'admin-balance-sheet-tabs-delete-post',
		 'uses' => 'BalanceSheetController@postBalanceSheetTabsDeleteView',
		 'permission' => 'Tabs Delete']);

		Route::post('tabs-multiple-delete/',
		['as' => 'admin-balance-sheet-tabs-delete-multiple-post',
		'uses' => 'BalanceSheetController@postBalanceSheetTabsDeleteMultipleView',
		'permission' => 'Tabs Delete']);
	});
