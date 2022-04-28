<?php

	Route::group(['prefix'	=>	'ajax', 'namespace' => '\App\Http\Controllers\Core\Company'], function() {
		Route::get('search-ajax-auto-suggest-search',
					['as'	=>	'search-ajax-auto-suggest-search',
					 'uses'	=>	'AjaxCompanyController@getAutosuggest']);
	});

	Route::group(['prefix' => 'admin/company-summary', 'route_group'=>'Company', 'middleware' => 'can:isAuthorized', 'namespace'	=>	'\App\Http\Controllers\Core\Company'], function() {
		Route::get('list/{company_id}',
		['as'	=>	'admin-company-summary-list-get',
		 'uses'	=>	'CompanyController@getSummaryListView',
		 'permission' => 'Company Summary List']);

		Route::get('create/{company_id}',
		['as'	=>	'admin-company-summary-create-get',
		 'uses'	=>	'CompanyController@getSummaryCreateView',
		 'permission' => 'Company Summary Create']);

		Route::post('create/{company_id}',
		['as'	=>	'admin-company-summary-create-post',
		 'uses'	=>	'CompanyController@postSummaryCreateView',
		 'permission' => 'Company Summary Create']);

		Route::get('edit/{company_id}/{id}',
		['as'	=>	'admin-company-summary-edit-get',
		 'uses'	=>	'CompanyController@getSummaryEditView',
		 'permission' => 'Company Summary Edit']);

		Route::post('edit/{company_id}/{id}',
		['as'	=>	'admin-company-summary-edit-post',
		 'uses'	=>	'CompanyController@postSummaryEditView',
		 'permission' => 'Company Summary Edit']);

		Route::post('delete/{company_id}/{id}',
		['as'	=>	'admin-company-summary-delete-post',
		 'uses'	=>	'CompanyController@postSummaryDeleteView',
		 'permission' => 'Company Summary Delete']);

		Route::post('delete-multiple/{company_id}',
		['as'	=>	'admin-company-summary-delete-multiple-post',
		 'uses'	=>	'CompanyController@postSummaryDeleteMultipleView',
		 'permission' => 'Company Summary Delete']);
	});

	Route::group(['prefix'	=>	'admin/company', 'route_group'=>'Company','middleware' => 'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Company'], function() {
		
		Route::get('list',
		['as'	=>	'admin-company-list-get',
		 'uses'	=>	'CompanyController@getListView',
		 'permission' => 'Company List']);

		Route::get('create',
		['as'	=>	'admin-company-create-get',
		 'uses'	=>	'CompanyController@getCreateView',
		 'permission' => 'Company Create']);

		Route::post('create',
		['as'	=>	'admin-company-create-post',
		 'uses'	=>	'CompanyController@postCreateView',
		 'permission' => 'Company Create']);

		Route::get('edit/{id}',
		['as'	=>	'admin-company-edit-get',
		 'uses'	=>	'CompanyController@getEditView',
		 'permission' => 'Company Edit']);

		Route::post('edit/{id}',
		['as'	=>	'admin-company-edit-post',
		 'uses'	=>	'CompanyController@postEditView',
		 'permission' => 'Company Edit']);

		Route::post('delete/{id}',
		['as'	=>	'admin-company-delete-post',
		 'uses'	=>	'CompanyController@postDeleteView',
		 'permission' => 'Company Delete']);

		Route::post('delete-multiple',
		['as'	=>	'admin-company-delete-multiple-post',
		 'uses'	=>	'CompanyController@postDeleteMultipleView',
		 'permission' => 'Company Delete']);

		//// These are for excel ///////
		Route::get('upload-quote',[
			'as'	=>	'admin-company-upload-quote-get',
			'uses'	=>	'CompanyController@getUploadQuote',
			'permission' => 'Upload Quote'
		]);

		Route::post('upload-quote',[
			'as'	=>	'admin-company-upload-quote-post',
			'uses'	=>	'CompanyController@postUploadQuote',
			'permission' => 'Upload Quote'
		]);

		Route::get('download-quote-upload-excel',[
			'as'	=>	'admin-company-download-quote-upload-excel',
			'uses'	=>	'CompanyController@downloadQuoteUpload',
			'permission' => 'Download Quote '
		]);

		Route::get('upload-financials',[
			'as'	=>	'admin-company-upload-financials-get',
			'uses'	=>	'CompanyController@getUploadFinancials',
			'permission' => 'Upload Financials '
		]);

		Route::post('upload-financials',[
			'as'	=>	'admin-company-upload-financials-post',
			'uses'	=>	'CompanyController@postUploadFinancials',
			'permission' => 'Upload Financials '
		]);

		Route::get('download-financials-upload-excel',[
			'as'	=>	'admin-company-download-financials-upload-excel',
			'uses'	=>	'CompanyController@downloadFinancialsUpload',
			'permission' => 'Download Financial '
		]);

		Route::get('upload-stock-price',[
			'as'	=>	'admin-company-upload-stock-price-get',
			'uses'	=>	'CompanyController@getUploadStockPrice',
			'permission' => 'Upload Stock Price'
		]);

		Route::post('upload-stock-price',[
			'as'	=>	'admin-company-upload-stock-price-post',
			'uses'	=>	'CompanyController@postUploadStockPrice',
			'permission' => 'Upload Stock Price'
		]);

		Route::get('download-stock-price-upload-excel',[
			'as'	=>	'admin-company-stock-price-upload-excel',
			'uses'	=>	'CompanyController@downloadUploadStockPrice',
			'permission' => 'Download Stock Price'
		]);

		Route::get('upload-balance-sheet/{compay_id}',[
			'as'	=>	'admin-company-upload-balance-sheet-get',
			'uses'	=>	'CompanyController@getUploadBalanceSheet',
			'permission' => 'Upload Company\'s Balance Sheet'
		]);

		Route::post('upload-balance-sheet/{compay_id}',[
			'as'	=>	'admin-company-upload-balance-sheet-post',
			'uses'	=>	'CompanyController@postUploadBalanceSheet',
			'permission' => 'Upload Company\'s Balance Sheet'
		]);

		Route::get('download-balance-sheet-upload-excel/{company_id}',[
			'as'	=>	'admin-company-balance-sheet-upload-excel',
			'uses'	=>	'CompanyController@downloadUploadBalanceSheet',
			'permission' => 'Download Company\'s Balance Sheet'
		]);

		Route::get('dividend-details/{company_id}', [
			'as'	=>  'admin-company-dividend-details-list',
			'uses'  =>  'CompanyController@getDividendDetailsListView',
			'permission' => 'View Company\'s Dividend Details'
		]);
		
		Route::post('divident-detail/delete/{dividend_detail_id}', [
			'as'	=>  'admin-company-dividend-detail-delete-post',
			'uses'  =>  'CompanyController@postDividendDetailDeleteView',
			'permission' => 'Delete Company\'s Dividend Details'
		]);

		Route::post('divident-detail/delete-multiple/', [
			'as'	=>  'admin-company-dividend-detail-delete-multiple-post',
			'uses'  =>  'CompanyController@postDividendDetailDeleteMultipleView',
			'permission' => 'Delete Company\'s Dividend Details'
		]);
		//// These are for excel ///////
		Route::post('upload-balance-sheet-headings/{compay_id}',[
			'as'	=>	'admin-company-upload-balance-sheet-post',
			'uses'	=>	'CompanyController@postUploadBalanceSheetHeadings',
			// 'permission' => 'Company  Upload Balance Sheet'
		]);

		Route::get('upload-dividend/{company_id}',[
			'as'	=>	'admin-company-upload-dividend-get',
			'uses'  =>  'CompanyController@getUploadDividend',
			'permission' => 'Upload Company\'s Dividend'
		]);

		Route::get('download-dividend-upload-excel/{company_id}',[
			'as'	=>	'admin-company-dividend-upload-excel',
			'uses'	=>	'CompanyController@downloadUploadDividend',
			'permission' => 'Download Company\'s Dividend '
		]);

		Route::post('upload-dividend/{company_id}', [
			'as'	=>	'admin-company-upload-dividend-post',
			'uses'  =>	'CompanyController@postUploadDividend',
			'permission' => 'Upload Company\'s Dividend '
		]);

		Route::get('fair-value/{company_id}',[
			'as'	=>	'admin-company-fair-value-get',
			'uses'	=>	'CompanyController@getFairValueView',
			'permission' => 'Company View Fair Value'
		]);

		Route::post('fair-value/ratings/update/{company_id}',[
			'as'	=>	'admin-company-rating-update-post',
			'uses'	=>	'CompanyController@postFairValueRatingUpdate',
			'permission' => 'Company Update Fair Value'
		]);

		Route::post('fair-value/expert/update/{company_id}', [
			'as'	=>	'admin-company-expert-update-post',
			'uses'	=>	'CompanyController@postExpertUpdate',
			'permission' => 'Company Update Expert'
		]);

		Route::get('quote/tab/list', [
		 	'as'	=>	'admin-company-quote-list-get',
			'uses'	=>	'CompanyController@getQuoteHeadingsView',
			'permission' => 'Company View Quote Headings'
		]);
		Route::get('quote/list/{tab_id}', [
			'as'	=>	'admin-company-quote-headings-list-get',
			'uses'	=>	'CompanyController@getQuoteHeadingsListView',
			'permission' => 'Company View Quote Headings'
		]);

		Route::post('quote/create', [
			'as'	=>	'admin-company-quote-headings-create-post',
			'uses'	=>	'CompanyController@postQuoteHeadingsCreateView',
			'permission' => 'Company Create Quote'
		]);
		Route::post('quote/edit/{quote_id}',[
			'as'	=>	'admin-company-quote-headings-edit-post',
			'uses'	=>	'CompanyController@postQuoteHeadingsEditView',
			'permission' => 'Company Edit Quote Headings'
		]);
		Route::post('quote/delete/{quote_id}', [
			'as'	=>	'admin-company-quote-headings-delete-post',
			'uses'	=>	'CompanyController@postQouteHeadingsDeleteView',
			'permission' => 'Company Delete Quote'
		]);
		Route::post('quote/multiple-delete', [
			'as'	=>	'admin-company-quote-headings-multiple-delete-post',
			'uses'	=>	'CompanyController@postQuoteHeadingsMultipleDeleteView',
			'permission' => 'Company Delete Quote '
		]);

		Route::get('financial-headings/{company_id}/{tab_id}', [
			'as'	=>	'admin-company-financial-tabs-headings-get',
			'uses'	=>	'CompanyController@getFinancialTabsHeadingsListView',
			'permission' => 'Company Select Financial Headings'
		]);

		Route::post('financial-headings/{company_id}/{tab_id}', [
			'as'	=>	'admin-company-financial-tabs-headings-post',
			'uses'	=>	'CompanyController@postFinancialTabsHeadingsListView',
			'permission' => 'Company Select Financial Headings'
		]);

		Route::post('financial-headings-refresh/{company_id}', [
			'as'	=>	'admin-company-financial-tabs-headings-refresh-post',
			'uses'	=>	'CompanyController@postFinancialTabsHeadingsRefresh',
			'permission' => 'Company Select Financial Headings'
		]);
	});

	Route::group(['namespace' => '\App\Http\Controllers\Core\Company'], function() {
		Route::get('view-company/{id}', [
			'as'	=>	'frontend-view-company',
			'uses'	=>	'CompanyController@getViewCompany'
		]);

		Route::get('company', [
			'as'	=>	'frontend-company',
			'uses'	=>	'CompanyController@getCompany'
		]);
	});

	