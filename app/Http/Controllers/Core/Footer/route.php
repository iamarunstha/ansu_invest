<?php
Route::group(['prefix'	=>	'admin/footer','route_group'=>'Footer', 'middleware'=>'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\Footer'], function() {
		
    Route::get('links/list', [
        'as'	=>	'admin-footer-get',
        'uses'	=>	'FooterController@getFooter',
        'permission' => 'View Footer']);

    Route::post('links/update', [
        'as' => 'admin-footer-links-edit-post',
        'uses' => 'FooterController@postUpdateFooterLinks',
        'permission' => 'Update Footer Links'
    ]);

    Route::post('links/delete/{id}', [
        'as' => 'admin-footer-links-delete-post',
        'uses' => 'FooterController@postDeleteFooterLinks',
        'permission' => 'Delete Footer Links'
    ]);

    Route::post('links/multiple-delete/', [
        'as' => 'admin-footer-links-delete-multiple-post',
        'uses' => 'FooterController@postDeleteMultipleFooterLinks',
        'permission' => 'Delete Footer Links'
    ]);

    Route::post('links/add/', [
        'as' => 'admin-footer-links-add-post',
        'uses' => 'FooterController@postAddFooterLinks',
        'permission' => 'Add Footer Links'
    ]);

    Route::get('contacts/', [
        'as' => 'admin-footer-contacts-get',
        'uses' => 'FooterController@getContacts',
        'permission' => 'View Footer Contacts and Disclaimer'
    ]);

    Route::post('contacts/', [
        'as' => 'admin-footer-contacts-post',
        'uses' => 'FooterController@postContacts',
        'permission' => 'Change Footer Contacts and Disclaimer'
    ]);
});
