<?php

namespace App\Http\Controllers\Core\Performance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\Performance\PerformanceModel;
use App\Http\Controllers\Core\Performance\PerformanceDataModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Company\SubFiscalYearModel;


class PerformanceController extends Controller
{
	public $view = 'Core.Performance.backend.';

	public function getPerformanceHeadingsView($sector_id)
	{
		$sector = SectorModel::where('id', $sector_id)->first();
		$headers = $data = PerformanceModel::with('subheadings')
                                    ->where('sector_id', $sector_id)
                                    ->whereNull('parent_id')
									->orderBy('ordering', 'ASC')
									->get();

		return view($this->view.'edit') 
			->with('sector', $sector)
			->with('data', $data)
            ->with('headers', $headers);
	}

	public function postPerformanceHeadingsView($sector_id)
	{
		$data = request()->all();
		\DB::beginTransaction();

		foreach ($data['data'] as $id => $row) {
            $validator = \Validator::make($row, (new PerformanceModel)->getPerformanceHeadingRule());
            
            if ($validator->fails()){
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
            }
			PerformanceModel::where('id', $id)
								->update($row);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Headings successfully updated');

		return redirect()->back();
	}

	public function postPerformanceHeadingsCreateView($sector_id)
	{
		$data = request()->all()['data'];

        $validator = \Validator::make($data, (new PerformanceModel)->getPerformanceHeadingRule());
        
        if ($validator->fails()){
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }

		$data['sector_id'] = $sector_id;

		PerformanceModel::create($data);

		\Session::flash('success-msg', 'Headings successfully created');

		return redirect()->back();
	}

	public function postPerformanceHeadingsDeleteView($heading_id)
	{
		$heading = PerformanceModel::where('id', $heading_id);

		$heading->delete();

		\Session::flash('success-msg', 'Headings successfully deleted');
		return redirect()->back();
	}

    public function getUploadPerformance($company_id){
        $view = [];
        $view['fiscal_year'] = FiscalYearModel::orderBy('ordering', 'ASC')->get();
        $view['sub_fiscal_year'] = SubFiscalYearModel::orderBy('group_under', 'ASC')->orderBy('ordering', 'ASC')->get();
        $view['company_id'] = $company_id;

        return view($this->view.'upload_performance')
                ->with($view); 

    }

    public function postUploadPerformance($company_id){
        $input = request()->all();
        $start_fiscal_year_id = $input['start_fiscal_year_id'];
        $end_fiscal_year_id = $input['end_fiscal_year_id'];

        $start_sub_year_id = $input['start_sub_year_id'];
        $end_sub_year_id = $input['end_sub_year_id'];

        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);


        $company = CompanyModel::where('id', $company_id)->firstOrFail();

	    $all_years = [];
        foreach(FiscalYearModel::select('id', 'fiscal_year')->orderBy('id')->get() as $_year){
	                        
        	$all_years[$_year->fiscal_year] = $_year->id;
	    }

	    $all_sub_years = [];
	    foreach (SubFiscalYearModel::orderBy('id')->get() as $_sub_year){
	    	$all_sub_years[$_sub_year->title] = $_sub_year->id;
	    }

        \DB::beginTransaction();
        foreach($data as $tab => $sheet) {

            foreach($sheet as $row) {
                
                if($row['ID']) {
					foreach ($row as $row_heading => $value){
	                    if(in_array($row_heading, ['ID', 'Particular']))
	                    	continue;

	                    $year_group = explode('#', $row_heading);

	                    $year = $year_group[0];
	                    $sub_year = isset($year_group[1]) ? $year_group[1] : NULL;

	                    if (isset($all_years[$year])) {
	                    	
	                        $fiscal_year_id = $all_years[$year];
	                        
	                        $sub_year_id = strlen($sub_year) ? $all_sub_years[$sub_year] : NULL;
	                         
	                        $record = PerformanceDataModel::firstOrNew([
	                            'heading_id' => $row['ID'],
	                            'company_id'    =>  $company_id,
	                            'fiscal_year_id'    =>  $fiscal_year_id,
	                            'sub_year_id'   =>  $sub_year_id

	                        ]);

	                        $record->value = $value;
	                        $record->save();
	                    } 
	                }                	
                }
            }
        }

        session()->flash('success-msg', 'Performance successfully updated');
        \DB::commit();

        return redirect()->back();
    }
    public function downloadUploadPerformance($company_id) { 
        $input = request()->all();

        $company = CompanyModel::where('id', $company_id)->firstOrFail();

        $tabs = ['Performance'];
        $_performance_headings = PerformanceModel::where('sector_id', $company->sector_id)
                                                ->with('subheadings')
                                                ->whereNull('parent_id')
                                                ->orderBy('ordering', 'ASC')
                                                ->get();

        $performance_headings = [];
        foreach($_performance_headings as $p) {
            $performance_headings[] = $p;
            foreach($p->subheadings->sortBy('ordering') as $_p) {
                $performance_headings[] = $_p;
            }
        }

        $download_performance_rule = (new PerformanceModel)->getDownloadPerformanceRule();
        $validator = \Validator::make($input, $download_performance_rule);

        if ($validator->fails()){

        	return redirect()->back()
        					->withInput()
        					->withErrors($validator);
        }



        $start_fiscal_year = FiscalYearModel::where('id', $input['start_fiscal_year_id'])->first()->ordering;
        $end_fiscal_year = FiscalYearModel::where('id', $input['end_fiscal_year_id'])->first()->ordering;

        $_fiscal_years = FiscalYearModel::where('ordering', '>=', $start_fiscal_year)
        								->where('ordering', '<=', $end_fiscal_year)
        								->orderBy('ordering', 'ASC')
        								->get();



        $fiscal_years = [];
        foreach($_fiscal_years as $f) {
        	$fiscal_years[$f->id] = $f->fiscal_year;
        }

        $sub_years = [];
        if($input['start_sub_year_id']) {
        	$start_sub_year = SubFiscalYearModel::where('id', $input['start_sub_year_id'])->first()->ordering;
        	$end_sub_year = SubFiscalYearModel::where('id', $input['end_sub_year_id'])->first()->ordering;
        	$_sub_year = SubFiscalYearModel::where('ordering', '>=', $start_sub_year)
        									->where('ordering', '<=', $end_sub_year)
        									->orderBy('ordering', 'ASC')
        									->get();

        	foreach($_sub_year as $s) {
        		$sub_years[$s->id] = $s->title;
        	}
        }

        $performance_data = PerformanceDataModel::whereIn('fiscal_year_id', array_keys($fiscal_years));

        if(count($sub_years)) {
        	$performance_data = $performance_data->whereIn('sub_year_id', array_keys($sub_years));
        } else {
        	$performance_data = $performance_data->whereNull('sub_year_id');
        }

        $performance_data = $performance_data->where('company_id', $company_id)->get();
        $_performance_data = [];
        foreach($performance_data as $v) {
        	$year_index = '';
        	$year_index = isset($fiscal_years[$v->fiscal_year_id]) ? $fiscal_years[$v->fiscal_year_id].'#' : '';
        	$year_index = isset($sub_years[$v->sub_year_id]) ? $year_index.$sub_years[$v->sub_year_id] : $year_index;
        	$_performance_data[$v->heading_id][$year_index] = $v->value;
        }

        $spreadsheet = [];

        $data = ['title' => $tabs[0], 'data' => [], 'styles' => []];
        $data['data'][0] = ['ID', 'Particular'];
        $data['styles'][0] = 'bold';

        foreach ($fiscal_years as $fiscal_year_title){
        	
        	$temp = $fiscal_year_title.'#';
        	foreach($sub_years as $sub_year_title) {
        		$_temp[] = $temp.$sub_year_title;
        	}

        	if(isset($_temp)) {
        		foreach($_temp as $t) {
        			$data['data'][0][] = $t;
        		}
        		$_temp = [];
        	} else {
        		$data['data'][0][] = $temp;
        	}
        }

        foreach($performance_headings as $v) {
        	$temp = [$v->id, $v->heading];
        	foreach($data['data'][0] as $index => $years) {
        		if($index < 2)
        			continue;

        		$temp[] = isset($_performance_data[$v->id][$years]) ? $_performance_data[$v->id][$years] : '-';
        	}
        	$data['data'][] = $temp;
        	$data['styles'][] = [$v->style];
        }
        $spreadsheet[] = $data;
		
		(new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Peformance-Sheet');        

    }                                                             
}