<?php

namespace App\Http\Controllers\Core\FiscalYear\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;

class ApiFiscalYearController extends Controller
{
	public function getFiscalYearList(){
		$fiscal_years = FiscalYearModel::orderBy('ordering')->get();
		return $fiscal_years;
	}
}
