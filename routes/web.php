<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    define('DS', "\\");
} else {
    // define('DS', "/");
}

Route::get('/', function () {
    // return view('frontend.index');
    return redirect('/home');
})->name('index');

Route::get('/get-image/{asset_type}/{filename?}/{size?}',
	['as'	=>	'get-image-asset-type-filename',
	 'uses'	=>	'ImageController@getAsset']);

Route::get('/ip-addresses', [
    'uses'  =>  'LoginController@getUserIp'
]);

require_once('ajax-route.php');

require_once('backend-route.php');
