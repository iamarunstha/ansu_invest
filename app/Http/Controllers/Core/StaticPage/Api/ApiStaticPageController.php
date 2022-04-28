<?php

namespace App\Http\Controllers\Core\StaticPage\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\StaticPage\DefinitionModel;
use Illuminate\Http\Request;
use Validator;


class ApiStaticPageController extends Controller
{
	public function getStaticPage($id) {
		$query = request()->get('query', Null);
		
		$content = DefinitionModel::where('page_id', $id)->select('initial', 'term', 'definition');		
		if($query){
			$content = $content->where('term', 'like', '%'.$query.'%');
		}
		$content = $content->get();
		$definitions = $content->groupBy('initial');

		return $definitions;
	}
}