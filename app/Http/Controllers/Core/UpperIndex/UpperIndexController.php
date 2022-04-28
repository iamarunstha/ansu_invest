<?php

namespace App\Http\Controllers\Core\UpperIndex;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\UpperIndex\UpperIndexModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\ExcelController;

class UpperIndexController extends Controller
{
	public $view = 'Core.UpperIndex.backend.';

	public function getUploadNepse(){ 
		return view($this->view.'upload');
 	}

 	public function postUploadNepse() {
 		$input = request()->all();

		$data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);

		try {
			\DB::beginTransaction();
			UpperIndexModel::where('as_on', $input['data']['date'])->delete();

			foreach($data as $sheet => $rows) {
				if($sheet == 'UpperIndex'){
					foreach($rows as $index => $row) {
						if(strlen($row['Name']) && strlen($row['Price']) && strlen($row['Percent'])) {
							UpperIndexModel::create([
								'name'	=>	$row['Name'],
								'price'	=>	$row['Price'],
								'percent' =>  $row['Percent']*100,
								'as_on'	=>	$input['data']['date']
							]);
						}
					}
				}
				else{
					if(count($rows)){
						NepseIndexModel::where('as_on', $input['data']['date'])->delete();

						foreach ($rows as $index => $row) {
							if ($row['Nepse Index'] && $row['Percent Change'] && $row['Point Change'] && $row['Turnover'] && $row['Volume']){
								NepseIndexModel::create([
									'nepse_index' => $row['Nepse Index'],
									'percent_change' => $row['Percent Change'],
									'point_change' => $row['Point Change'],
									'turnover' => $row['Turnover'],
									'volume' => $row['Volume'],
									'as_on' => $input['data']['date']
								]);					
							}
						}
					}
				}
			}
			\Session::flash('success-msg', 'successfully uploaded');
			\DB::commit();	
		} catch(\Exception $e) {
			\Session::flash('friendly-error-msg', $e->getMessage());
		}
		return redirect()->back();
 	}

 	public function getDownloadUpperNepse() {
 		$as_on = request()->get('date', null);
 		$as_on = is_null($as_on)  ? \Carbon\Carbon::now()->format('Y-m-d') : $as_on;
 		$tabs = ['UpperIndex', 'NepseIndex'];
		$data = [];

		foreach ($tabs as $tab){
			$sheet = [];
			$sheet['title'] = $tab;
			$sheet_headings = $tab == 'UpperIndex' ? ['id', 'Name', 'Price', 'Percent'] : ['Nepse Index' , 'Point Change', 'Percent Change', 'Turnover', 'Volume'];
			$sheet_data = [$sheet_headings];

			if($tab == 'UpperIndex'){
				$_data = UpperIndexModel::where('as_on', $as_on)->get();
				foreach($_data as $d) {
					$sheet_data[] = [
						$d->id, $d->name, $d->price, $d->percent
					];
				}
			}else{
				$_data = NepseIndexModel::where('as_on', $as_on)->get();

				foreach($_data as $d) {
					$sheet_data[] = [
						$d->nepse_index, $d->point_change, $d->percent_change, $d->turnover, $d->volume
					];
				}
			}

			$sheet['data'] = $sheet_data;
			$data[] = $sheet;
		}
		(new ExcelController)->apiDownloadExcel($data, $filename='nepse-excel-'.$as_on, $styles = []);
	}
}
