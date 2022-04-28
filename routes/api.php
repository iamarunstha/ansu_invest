<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/user-login',
	['uses'	=>	'LoginController@postApiLogin']);

Route::post('v1/subscribe',
	['uses'	=>	'LoginController@postApiRegister']);

Route::post('v1/forgot-password',
	['uses'	=>	'LoginController@postForgotPassword']);

Route::post('v1/change-password',
	[
		'uses'	=>	'LoginController@postApiChangePassword',
		'middleware'	=>	'jwt'
	]);

Route::get('v1/refresh',
	[
		'uses'	=>	'LoginController@refresh',
		'middleware'	=>	'jwt'
	]);

foreach (glob(base_path()."/app/Http/Controllers/Core/*/Api/api-route.php") as $filename) {
    require_once($filename);
}
