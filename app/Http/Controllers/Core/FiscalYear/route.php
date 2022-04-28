<?php
	Route::group(['prefix'	=>	'admin/fiscal-year','route_group'=>'Fiscal Year', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\FiscalYear'], function() {
		
		Route::get('list',
		['as'	=>	'admin-fiscal-year-list-get',
		 'uses'	=>	'FiscalYearController@getListView',
		 'permission' => 'Fiscal Year List']);

		Route::get('create',
		['as'	=>	'admin-fiscal-year-create-get',
		 'uses'	=>	'FiscalYearController@getCreateView',
		 'permission' => 'Fiscal Year Create']);

		Route::post('create',
		['as'	=>	'admin-fiscal-year-create-post',
		 'uses'	=>	'FiscalYearController@postCreateView',
		 'permission' => 'Fiscal Year Create']);

		Route::get('ordering',
		['as'	=>	'admin-fiscal-year-edit-ordering-get',
		 'uses'	=>	'FiscalYearController@getOrderingEditView',
		 'permission' => 'Fiscal Year Edit Ordering']);

		Route::post('ordering',
		['as'	=>	'admin-fiscal-year-edit-ordering-post',
		 'uses'	=>	'FiscalYearController@postOrderingEditView',
		 'permission' => 'Fiscal Year Edit Ordering']);

		Route::get('edit/{id}',
		['as'	=>	'admin-fiscal-year-edit-get',
		 'uses'	=>	'FiscalYearController@getEditView',
		 'permission' => 'Fiscal Year Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-fiscal-year-edit-post',
		 'uses'	=>	'FiscalYearController@postEditView',
		 'permission' => 'Fiscal Year Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-fiscal-year-delete-post',
		 'uses'	=>	'FiscalYearController@postDeleteView',
		 'permission' => 'Fiscal Year Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-fiscal-year-delete-multiple-post',
		 'uses'	=>	'FiscalYearController@postDeleteMultipleView',
		 'permission' => 'Fiscal Year Delete']);

		Route::get('assign-companies/{id}',
		['as'	=>	'admin-fiscal-year-company-assign-get',
		 'uses' =>	'FiscalYearController@getCompanyAssignView',
		 'permission' => 'Fiscal Year Assign Company']);

		Route::post('assign-companies/{id}',
		['as'	=>	'admin-fiscal-year-company-assign-post',
		 'uses' =>	'FiscalYearController@postCompanyAssignView',
		 'permission' => 'Fiscal Year Assign Company']);
	});
