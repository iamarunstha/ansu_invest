<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

class AjaxController extends Controller
{
	public function postUploadImage()
	{
		$input = request()->all();
		$image = $input['image'];
		$input['set-sizes'] = isset($input['set-sizes']) && $input['set-sizes'] == 'no' ? false : true;
		$directory = $input['directory'];
		$response = (new ImageController)->uploadAPI($image, $directory, $input['asset_type'], $input['set-sizes']);

		if($response['status'])
		{
			return response()->json($response);
		}
		else
		{
			return response()->json($response, 500);
		}
	}

	public function postUploadAsset()
	{
		$input = request()->all();
		$file = $input['file'];
		//$input['set-sizes'] = isset($input['set-sizes']) && $input['set-sizes'] == 'no' ? false : true;
		$directory = $input['directory'];

		$response = (new VideoController)->uploadAPI($file, $directory, $input['asset_type']);

		if($response['status'])
		{
			return response()->json($response);
		}
		else
		{
			return response()->json($response, 500);
		}
	}
}