<?php

namespace App\Http\Controllers\Core\Valuation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\Valuation\ValuationModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use App\Http\Controllers\Core\Valuation\ValuationDataModel;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Company\SubFiscalYearModel;


class ValuationController extends Controller
{
	public $view = 'Core.Valuation.backend.';

	public function getValuationHeadingsView($sector_id)
	{
		$sector = SectorModel::where('id', $sector_id)->first();

		$data = ValuationModel::where('sector_id', $sector_id)

									->orderBy('ordering', 'ASC')
									->get();

		return view($this->view.'edit') 
			->with('sector', $sector)
			->with('data', $data);
	}

	public function postValuationHeadingsView($sector_id)
	{
		$data = request()->all();
		\DB::beginTransaction();

		foreach ($data['data'] as $id => $row) {
			ValuationModel::where('id', $id)
								->update($row);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Headings successfully updated');

		return redirect()->back();
	}

	public function postValuationHeadingsCreateView($sector_id)
	{
		$data = request()->all()['data'];

		$data['sector_id'] = $sector_id;

		ValuationModel::create($data);

		\Session::flash('success-msg', 'Headings successfully created');

		return redirect()->back();
	}

	public function postValuationHeadingsDeleteView($heading_id)
	{
		$heading = ValuationModel::where('id', $heading_id);

		$heading->delete();

		\Session::flash('success-msg', 'Headings successfully deleted');
		return redirect()->back();
	}

    public function getUploadValuation($company_id){
        $view = [];
        $view['fiscal_year'] = FiscalYearModel::orderBy('ordering', 'ASC')->get();
        $view['sub_fiscal_year'] = SubFiscalYearModel::orderBy('group_under', 'ASC')->orderBy('ordering', 'ASC')->get();
        $view['company_id'] = $company_id;

        return view($this->view.'upload-valuation')
                ->with($view); 

    }

    public function postUploadValuation($company_id){
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
	                         
	                        $record = ValuationDataModel::firstOrNew([
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

        session()->flash('success-msg', 'Valuation successfully updated');
        \DB::commit();

        return redirect()->back();
    }
    public function downloadUploadValuation($company_id) { 

        $input = request()->all();

        $company = CompanyModel::where('id', $company_id)->firstOrFail();

        $tabs = ['valuation'];
        $valuation_headings = ValuationModel::where('sector_id', $company->sector_id)
                                                                    ->orderBy('ordering', 'ASC')
                                                                    ->get();

        $download_valuation_rule = (new ValuationModel)->getDownloadValuationRule();
        $validator = \Validator::make($input, $download_valuation_rule);

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

        $valuation_data = ValuationDataModel::whereIn('fiscal_year_id', array_keys($fiscal_years));

        if(count($sub_years)) {
        	$valuation_data = $valuation_data->whereIn('sub_year_id', array_keys($sub_years));
        } else {
        	$valuation_data = $valuation_data->whereNull('sub_year_id');
        }

        $valuation_data = $valuation_data->where('company_id', $company_id)->get();
        $_valuation_data = [];
        foreach($valuation_data as $v) {
        	$year_index = '';
        	$year_index = isset($fiscal_years[$v->fiscal_year_id]) ? $fiscal_years[$v->fiscal_year_id].'#' : '';
        	$year_index = isset($sub_years[$v->sub_year_id]) ? $year_index.$sub_years[$v->sub_year_id] : $year_index;
        	$_valuation_data[$v->heading_id][$year_index] = $v->value;
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

        foreach($valuation_headings as $v) {
        	$temp = [$v->id, $v->heading];
        	foreach($data['data'][0] as $index => $years) {
        		if($index < 2)
        			continue;

        		$temp[] = isset($_valuation_data[$v->id][$years]) ? $_valuation_data[$v->id][$years] : '-';
        	}
        	$data['data'][] = $temp;
        	$data['styles'][] = [$v->style];
        }

        //dd($valuation_headings);

        //dd($data);

        /*foreach ($fiscal_years as $fiscal_year_index => $fiscal_year){
        	$fiscal_year_id = FiscalYearModel::where('fiscal_year', $fiscal_year['year'])->first()->id;
        	$_company_valuation = ValuationDataModel::where('company_id', $company_id)->where('fiscal_year_id', $fiscal_year_id);

        	if (isset($fiscal_year['sub_year'])){
        		$sub_fiscal_year_id =SubFiscalYearModel::where('title', $fiscal_year['sub_year'])->first()->id;
        		$_company_valuation = $_company_valuation->where('sub_year_id', $sub_fiscal_year_id)->get();
        	}

        	$company_valuation = [];
        
        	foreach($_company_valuation as $b) {
            	$company_valuation[$b->heading_id] = $b;
        	}

//        	dd($company_valuation);

        	if(isset($valuation_headings)) {
            	foreach($valuation_headings as $index=>$heading) {

                	$data['data'][$index+1] = [$heading->id, $heading->heading];

                	if (isset($company_valuation[$heading->id])){

                		for ($i=2; $i<count($fiscal_years); $i++){
                			
                			if ($i==$fiscal_year_index+2){
 		               			array_push($data['data'][$index+1], $company_valuation[$heading->id]->value);


 		               		}else{
 		               			array_push($data['data'][$index+1], '-');

 		               		}
                		}

                	}
                	$data['styles'][$index+1] = $heading->style ? $heading->style : '-';
            	}
        	}
        }*/
        $spreadsheet[] = $data;
		
		(new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Valuation-Sheet');        

    }                                                             
}