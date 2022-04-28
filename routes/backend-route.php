<?php

Route::get('home',
['as'	=>	'admin-home',
 'uses'	=>	'LoginController@getHome'])->middleware('superadmin');

Route::get('/login',
['as'	=>	'login',
 'uses'	=>	'LoginController@getLogin']);

Route::post('/login',
['as'	=>	'login',
 'uses'	=>	'LoginController@postLogin']);

Route::post('/logout',
['as'	=>	'logout',
 'uses'	=>	'LoginController@postLogout']);

Route::get('/admin/change-password',
['as'	=>	'change-password-get',
 'uses'	=>	'LoginController@getChangePassword',
 'middleware'	=>	'auth']);

Route::post('/admin/change-password',
['as'	=>	'change-password-post',
 'uses'	=>	'LoginController@postChangePassword',
 'middleware'	=>	'auth']);

foreach (glob(base_path()."/app/Http/Controllers/Core/*/route.php") as $filename) {
	require_once($filename);
}