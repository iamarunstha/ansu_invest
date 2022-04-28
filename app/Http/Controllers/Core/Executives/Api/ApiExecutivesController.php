<?php 

namespace App\Http\Controllers\Core\Executives\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Executives\ExecutivesModel;
use App\Http\Controllers\Core\Executives\ExecutivesColumnModel;
use App\Http\Controllers\Core\Executives\ExecutivesTabModel;

class ApiExecutivesController extends Controller
{
	public function getExecutivesTabList(){
		$tabs = ExecutivesTabModel::orderBy('ordering')->get();
		return response()->json($tabs);
	}
	public function getExecutivesList($slug, $tab_id)
	{
		$company = CompanyModel::where('slug', $slug)->first();
		$_executive_columns = ExecutivesColumnModel::orderby('ordering', 'ASC')->get();
		$executive_columns = [];
		foreach($_executive_columns as $e) {
			if($e->column_name != 'Experience'){
				$executive_columns[$e->id] = ['name' => $e->column_name,
										 'type'	=>	$e->type];
			}
		}
		$executives = [];
		$_executives = ExecutivesModel::where('company_id', $company->id)->where('tab_id', $tab_id)->get();
		foreach($_executives as $e) {
			$executives[$e->row_id][] = $e;
		}
		
		$table = ['headers' => [], 'body'=>[]];
		foreach($executive_columns as $column_id => $e) {
			$table['headers'][] = [
				'key'	=>	$column_id,
				'alias'	=>	$e['name']

			];
		}

		$temp = [];
		foreach ($executives as $row_id => $executive){
			foreach($executive as $row)		{
				if(isset($executive_columns[$row->column_id])) {
					switch($executive_columns[$row->column_id]['type']) {
						case 'varchar':
							$temp[$row->column_id] = $row->value_string;	
							break;

						case 'integer':
							$temp[$row->column_id] = $row->value_int;
							break;

						case 'float':
							$temp[$row->column_id] = $row->value_float;
							if($row->value_float && $row->column_id == 4){ // for Share %
								$temp[$row->column_id] = $temp[$row->column_id].'%';
							}
							break;

						case 'text':
							$temp[$row->column_id] = $row->value_text;
							break;

						default:
							$temp[$row->column_id] = $row->value_string;	
					}
				} else {
					$temp[$row->column_id] = $row->value_string;	
				}
				
			}
			$table['body'][] = $temp;
			$temp = [];
		}

		return $table;

		return response()->json($table);
	}

}