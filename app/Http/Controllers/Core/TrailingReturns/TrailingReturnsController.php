<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use App\Http\Controllers\Core\TrailingReturns\CompanyTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\NepseTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\SectorTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\TrailingReturnsColumnModel;
use App\Http\Controllers\Core\TrailingReturns\TrailingReturnsTabModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TrailingReturnsController extends Controller
{
	public $view = 'Core.TrailingReturns.backend.';

	public function getTrailingReturnsUploadView(){ 
		return view($this->view.'upload-trailing-returns');
 	}

 	public function getTrailingReturnsDownloadExcel(){
 		$tab_group = ['Price Return'=>
 					['Sector-price-return','Companies-price-return', 'Nepse-price-return'],
 				'Total Return'=>
 					['Sector-total-return','Companies-total-return', 'Nepse-total-return']
 				];

 		$column_headings = TrailingReturnsColumnModel::orderBy('ordering', 'ASC')->get();

 		$types = TrailingReturnsTypeModel::get();

 		$nepse_trailing_returns = NepseTrailingReturnsModel::get();
 							
 		$companies = CompanyModel::with('trailingReturns')->get();
 		$sectors = SectorModel::with('trailingReturns')->get();
 		
 		$spreadsheet = [];
 		foreach ($tab_group as $_type=>$tabs){
 			$type = TrailingReturnsTypeModel::where('type_name',$_type)->first();
 			foreach($tabs as $tab){
 				$data = ['title' => $tab, 'data' => []];
 				$temp_header = ['ID'];
 				if(Str::startsWith($tab,'Companies')){
 					$temp_header[] = 'Company';
 				}
 				if(Str::startsWith($tab,'Sector')){
 					$temp_header[] = 'Sector';
 				}

 				foreach ($column_headings as $h){
 					$temp_header[] = $h->column_name;
 				}
 				$data['data'][] = $temp_header;
	
 				if (Str::startsWith($tab,'Sector')){
 					foreach($sectors as $s){
	 					$column_objects = $s->trailingReturns->where('type_id', $type->id);
	 					$temp = [];
	 					try {
	 						foreach($data['data'][0] as $index=>$heading){
								if ($heading == "ID")
									$temp[] = $s->id;
								else if($heading == 'Sector')
									$temp[] = $s->name;
		 						else{
		 							$added=false;
		 							foreach($column_objects as $col){
		 								if ($heading==TrailingReturnsColumnModel::where('id', $col->column_id)->first()->column_name){
		 									$temp[] = $col->value;
		 									$added=true;
		 								}
		 							}
		 							if(!$added)
		 								$temp[] = '-';
		 						}
	 						}
	 						$data['data'][] = $temp;
	 					}
	 					catch(\Exception $e) {
	 						dd($heading);
	 					}
	 				}
	 			}

	 			if (Str::startsWith($tab,'Companies')){
	 				foreach($companies as $c){
	 					$column_objects = $c->trailingReturns->where('type_id', $type->id);
	 					$temp = [];
	 					try {
	 						foreach($data['data'][0] as $index=>$heading){
								if ($heading == "ID")
									$temp[] = $c->id;
								else if($heading == 'Company')
									$temp[] = $c->company_name;
		 						else{
		 							$added=false;
		 							foreach($column_objects as $col){
		 								if ($heading==TrailingReturnsColumnModel::where('id', $col->column_id)->first()->column_name){
		 									$temp[] = $col->value;
		 									$added=true;
		 								}
		 							}
		 							if(!$added)
		 								$temp[] = '-';
		 						}
	 						}
	 						$data['data'][] = $temp;
	 					}
	 					catch(\Exception $e) {
	 						dd($heading);
	 					}
	 				}
	 			}
	 			if (Str::startsWith($tab,'Nepse')){
	 				$temp = [];
	 				foreach($data['data'][0] as $index=>$heading){
	 					if($heading == 'ID'){
	 						$temp[] = '-';
	 					}
	 					else{
	 						$added = false;
	 						foreach($nepse_trailing_returns->where('type_id', $type->id) as $s){
	 							if ($heading == TrailingReturnsColumnModel::where('id', $s->column_id)->first()->column_name){
 									$temp[] = $s->value;
 									$added=true;
 								}
 							}
 							if (!$added)
 								$temp[] = '-';
 						}
 					}
 					$data['data'][] = $temp;
 				}
 				$spreadsheet[] = $data;
 			} 

 			
 		}
 		(new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Trailing-Returns');
 	}

 	public function postTrailingReturnsUploadView()
 	{
 		$input = request()->all();
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);

        $_columns = TrailingReturnsColumnModel::get();
        $columns = [];
        foreach($_columns as $c) {
        	$columns[$c->column_name] = $c->id;
        }
        \DB::beginTransaction();
        foreach ($data as $_tab => $sheet) {

        	switch($_tab) {
        		case 'Sector-price-return' : 
        			$type_id = 2;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
        						$credentials = [
		        					'sector_id' => $row['ID'],
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = SectorTrailingReturnsModel::firstOrNew($credentials);
	        					if ($row[$column_name]=='-'){
	        						if($data = SectorTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
	        						$record->value = $row[$column_name];
        							$record->save();
	        					}		
        					}		
        				}
        			}
        			
        		break;

        		case 'Companies-price-return' : 
        			$type_id = 2;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
        						$credentials = [
		        					'company_id' => $row['ID'],
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = CompanyTrailingReturnsModel::firstOrNew($credentials);
								if ($row[$column_name]=='-'){
	        						if($data = CompanyTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
	        						$record->value = $row[$column_name];
        							$record->save();
	        					}		
        					}
        					
        				}
        				
        			}
        			
        		break;

        		case 'Nepse-price-return' : 
        			$type_id = 2;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
        						$credentials = [
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = NepseTrailingReturnsModel::firstOrNew($credentials);
	        					if ($row[$column_name]=='-'){
	        						if($data = NepseTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
        							$record->value = $row[$column_name];
        							$record->save();		
        						}
        					}
        				}
        			}
        		break;

        		case 'Sector-total-return' : 
        			$type_id = 1;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
	        					$credentials = [
		        					'sector_id' => $row['ID'],
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = SectorTrailingReturnsModel::firstOrNew($credentials);
	        					if ($row[$column_name]=='-'){
	        						if($data = SectorTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
	        						$record->value = $row[$column_name];
        							$record->save();
	        					}		
        					}
        					
        				}
        				
        			}
        			
        		break;

        		case 'Companies-total-return' : 
        			$type_id = 1;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
	        					$credentials = [
		        					'company_id' => $row['ID'],
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = CompanyTrailingReturnsModel::firstOrNew($credentials);
								if ($row[$column_name]=='-'){
	        						if($data = CompanyTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
	        						$record->value = $row[$column_name];
        							$record->save();
	        					}
        					}
        					
        				}
        				
        			}
        			
        		break;

        		case 'Nepse-total-return' : 
        			$type_id = 1;
        			foreach($sheet as $row) {

        				foreach($columns as $column_name => $column_id) {
        					if(isset($row[$column_name])) {
	        					$credentials = [
		        					'column_id' => $column_id,
		        					'type_id' => $type_id
        						];
	        					$record = NepseTrailingReturnsModel::firstOrNew($credentials);
	        					if ($row[$column_name]=='-'){
	        						if($data = NepseTrailingReturnsModel::where($credentials)->first())
	        							$data->delete();
	        					}
	        					else{
        							$record->value = $row[$column_name];
        							$record->save();		
        						}
        					}        					
        				}
        			}
        		break;
        	}
        }
        	
        session()->flash('success-msg', 'Trailing returns successfully uploaded');
        \DB::commit();

        return redirect()->back();
 	}
}