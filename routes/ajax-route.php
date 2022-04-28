<?php

Route::post('ajax/upload-image',
['as'	=>	'ajax-upload-image-post',
 'uses'	=>	'AjaxController@postUploadImage']);

Route::post('ajax/upload-asset',
['as'	=>	'ajax-upload-asset-post',
 'uses'	=>	'AjaxController@postUploadAsset']);