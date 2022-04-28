<?php 

namespace App\Http\Controllers\Core\RelativeValuation\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\RelativeValuation\RelativeValuationModel;
use App\Http\Controllers\Core\Company\CompanyModel;

class ApiRelativeValuationController extends Controller
{
	public function getRelativeValuation($slug)
	{
		$columns = ['id', 'company_id', 'description', 'summary', 'title'];
		$company = CompanyModel::where('slug', $slug)->first();
		$valuation = RelativeValuationModel::select($columns)->where('company_id', $company->id)->first();

		return response()->json(['data' => $valuation]);
	}
}
