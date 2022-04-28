<?php

namespace App\Http\Controllers\Core\Graph\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\BalanceSheet\BalanceSheetModel;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetModel;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;

class ApiGraphController extends Controller
{
    public function getBalanceSheetGraph() {
        $companies = CompanyModel::orderBy('id')->get();
        $data = [];
        
        $headers = BalanceSheetModel::select('heading')
        				->where('in_graph', 'yes')
        				->get();

        foreach ($companies as $index => $company) {

        	foreach ($headers as $header){
        		$data[$header->heading][$index] = [
        			'company' => $company->company_name,
        		];
        		$selected_companies = CompanyBalanceSheetModel::where('company_id', 6)->get();
        		foreach ($selected_companies as $selected_company){

        			$year = FiscalYearModel::where('id',$selected_company->fiscal_year_id)->first()->fiscal_year;

        			$data[$header->heading][$index][$year] = $selected_company->value;
        		}
			}
        }
        
        return response()->json(['data' => $data]);
    }
}