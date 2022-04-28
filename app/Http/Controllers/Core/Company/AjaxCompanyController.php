<?php

namespace App\Http\Controllers\Core\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AjaxCompanyController extends Controller
{
	public function getAutosuggest() {
		$input = request()->all();
		$data = CompanyModel::where('company_name', 'LIKE', '%'.$input['term'].'%')
							->take(10)
							->orderBy('company_name', 'ASC')
							->get();
		$return = [];
		foreach($data as $d) {
			$return[] = ['id' => $d->id, 'label' => $d->company_name.'('.$d->short_code.')', 'url' => route('frontend-view-company', $d->id), 'is_all'	=>	false];
		}

		if(empty($return)) {
			$return[] = ['id' => 0, 'label' => 'No Company Found', 'url' => '#', 'is_all'	=>	false];
		}

		$return[] = ['id' => 0, 'label' => 'View All', 'url'	=>	route('frontend-company'), 'is_all'	=>	true];

		return $return;
	}
}