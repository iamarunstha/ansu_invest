<?php

namespace App\Http\Controllers\Core\MarketSummary;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\MarketSummary\MarketSummaryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\ExcelController;

class MarketSummaryController extends Controller
{
	public $view = 'Core.MarketSummary.backend.';

	public function getUploadNepse(){ 
		return view($this->view.'upload');
 	}

 	public function postUploadNepse() {
 		$input = request()->all();

		$data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);
		try {
			\DB::beginTransaction();
			MarketSummaryModel::where('as_on', $input['data']['date'])->delete();

			foreach($data as $sheet => $rows) {
				foreach($rows as $index => $row) {
					if(strlen($row['Name']) && strlen($row['Percent']) && strlen($row['Percent'])) {
						MarketSummaryModel::create([
							'name'	=>	$row['Name'],
							'price'	=>	$row['Price'],
							'percent'	=>	$row['Percent'],
							'as_on'	=>	$input['data']['date']
						]);
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
 		$data = 
 			[
 				'title' => 'Sheet',
 				'data'	=>	[
 					['id', 'Name', 'Price', 'Percent']
	 			]
	 		]
 		;

 		$_data = MarketSummaryModel::where('as_on', $as_on)
 								->get();

 		foreach($_data as $d) {
 			$data['data'][] = [
 				$d->id, $d->name, $d->price, $d->percent
 			];
 		}

 		
 		(new ExcelController)->apiDownloadExcel([$data], $filename='market-summary-excel-'.$as_on, $styles = []);

 	}
}