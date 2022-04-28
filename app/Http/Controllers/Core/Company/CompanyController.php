<?php

namespace App\Http\Controllers\Core\Company;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel;
use App\Http\Controllers\Core\Dividend\DividendColumnModel;
use App\Http\Controllers\Core\Dividend\DividendDetailModel;
use App\Http\Controllers\Core\Dividend\DividendModel;
use App\Http\Controllers\Core\Dividend\DividendTypeModel;
use App\Http\Controllers\Core\Experts\ExpertsModel;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanyController extends Controller
{
	public $view = 'Core.Company.backend.';
    public $frontend_view = 'Core.Company.frontend.';
	private $storage_folder = 'company';

    /////////// Frontend ////////////////////
    public function getViewCompany($id) {
        $view = (new CompanyModel)->getCompanyData($id);
        $view['summary'] = (new CompanySummaryModel)->getSummary($id);
        if(isset($view['summary']->description)) {
            unset($view['summary']->description);
        }
        
        return view($this->frontend_view.'view-company')
                ->with($view);
    }

    public function getCompany() {
        $view['company_types'] = CompanyTypeModel::get();
        return view($this->frontend_view.'company')
                ->with($view);
    }

    /////////// Frontend ////////////////////

    public function getListView() {
        $input = request()->all();
        $data = CompanyModel::with('getType')->with('getSector');

        if (isset($input['search'])){
            $data = $data->where('company_name','like', '%'.$input['search'].'%')
                         ->orWhere('short_code', 'like', '%'.$input['search'].'%');
        }
        $data = $data->orderBy('id', 'DESC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
    }

    public function getCreateView() {
        $core = $this->core;
        $core = $core.'Company\CompanyTypeModel';
        $types = $core::orderBy('type', 'ASC')->get();
        $sectors = CompanySectorModel::get();
        $dividend_types = DividendTypeModel::get();
        return view($this->view.'create')
                ->with('types', $types)
                ->with('sectors', $sectors)
                ->with('dividend_types', $dividend_types);
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new CompanyModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
           \DB::beginTransaction();
                if(isset($data['data']['asset'])) {
                    request()->file('data.asset')->store('company-logo');
                    $data['data']['asset'] = request()->file('data.asset')->hashName();
                }
                $news = CompanyModel::create($data['data']);
                $news->slug = \Str::slug($news->short_code.'-'.$news->id);
                $news->save();
            \DB::commit();
        }

        \Session::flash('success-msg', 'Company successfully created');

        return redirect()->back();
    }

    public function getEditView($id) {
        $data = CompanyModel::where('id', $id)->firstOrFail();

        $core = $this->core.'Company\CompanyTypeModel';
        $types = $core::orderBy('type', 'ASC')->get();
        $sectors = CompanySectorModel::get();
        $dividend_types = DividendTypeModel::get();

        return view($this->view.'edit')
                ->with('data', $data)
                ->with('types', $types)
                ->with('sectors', $sectors)
                ->with('dividend_types', $dividend_types);
    }

    public function postEditView($id) {
        $original_data = CompanyModel::where('id', $id)->firstOrFail();
        $news_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new CompanyModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data['data']['slug'] = \Str::slug($data['data']['short_code'].'-'.$id);
            if(request()->hasFile('data.asset')) {
                request()->file('data.asset')->store('company-logo');
                $data['data']['asset'] = request()->file('data.asset')->hashName();    
            } else {
                $data['data']['asset'] = $original_data->asset;
            }
            CompanyModel::where('id', $id)->update($data['data']);

            \Session::flash('success-msg', 'Company successfully updated');

            return redirect()->back();
        }
    }

    public function postDeleteView($id) {
        
        $response = $this->apiDelete($id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-error-msg']);
        }

        return redirect()->back();
    }

    public function postDeleteMultipleView() {
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDelete($id) {
        try {
            $data = CompanyModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Company could not be deleted'];
        }

        return ['status' => true, 'message' => 'Company successfully deleted'];
        
    }

    public function postSetAsTopCompanyView($id) {
        $msg = '';
        $data = CompanyModel::where('id', $id)->firstOrFail();
        if($data->is_top_news == 'yes') {
            $msg = 'Unset As Top Company';
            $data->is_top_news = 'no';
        } else {
            $msg = 'Set As Top Company';
            $data->is_top_news = 'yes';
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();
    }

    public function getUploadStockPrice() {
        return view($this->view.'upload-stock-price');
    }

    public function postUploadStockPrice() {
        $input = request()->all();
        
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']); 

        foreach($data as $sheetname => $dd) {
            foreach($dd as $d) {

                if(!is_null($d['ID'])) {

                    foreach (array_keys($d) as $column_name){
                        if (in_array($column_name, ['ID', 'Company_name']))
                            continue;

                        if ($d[$column_name] != '-'){
                            $row = CompanyPriceModel::firstOrNew([
                                'price_at' => $column_name,
                                'company_id'    =>  $d['ID']
                            ]);
                            $row->price = $d[$column_name] == '-' ? NULL : $d[$column_name];
                            $row->save();
                        }
                    }
                }
            }
        }
        
        session()->flash('success-msg', 'Stock-price successfully updated');

        return redirect()->back();
    }

    public function downloadUploadStockPrice() {

        $input = request()->all();

        $start_date = $input['start_date'];
        $end_date = $input['end_date'];
        $date_range = CarbonPeriod::create($start_date, $end_date);
 
        $company = CompanyModel::orderBy('company_name', 'ASC')->get();

        $spreadsheet = [];
        $data = ['title' => 'Company', 'data' => []];

        $data['data'][0] = ['ID', 'Company_name'];

        $_date_range = [];
        foreach ($date_range as $date){
            
            $data['data'][0][] = $_date_range[] = $date->format('Y-m-d');
        }
        
        $_existing_prices = CompanyPriceModel::whereIn('price_at', $_date_range)
                                            ->get();

        $existing_prices = [];
        foreach($_existing_prices as $e) {
            $existing_prices[$e->company_id][$e->price_at] = $e->price;
        }

        foreach($company as $c) {
            $temp = [$c->id, $c->company_name];
            

            foreach($_date_range as $date){
                $price = isset($existing_prices[$c->id][$date]) ? $existing_prices[$c->id][$date] : '-';
                $temp[] = $price;
            }

            $data['data'][] = $temp;
        }

        $spreadsheet[] = $data;
       
        (new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Price-of-Stock');
    }

    public function downloadQuoteUpload() {
        $company = CompanyModel::orderBy('company_name', 'ASC')->get();
        $tabs = CompanyQuoteTabModel::orderBy('ordering', 'ASC')->get();
        $_excel_data = CompanyQuoteValuesModel::get();
        $excel_data = [];
        foreach($_excel_data as $e) {
            $excel_data[$e->company_id][$e->quote_id] = $e;
        }

        $data_rows = [];


        $spreadsheet = [];
        foreach($tabs as $tab_index => $t) {
            $quote_headings = CompanyQuoteModel::where('tab_id', $t->id)->get();
            $data = ['title' => $t->tab_name, 'data' => []];
            $heading_columns_of_excel = ['ID', 'Company Name'];
            foreach($quote_headings as $quote_heading) {
                $heading_columns_of_excel[] = $quote_heading->display_name;
                if(!is_null($quote_heading->options)) {
                    $options = json_decode($quote_heading->options);
                    if(!is_null($options)) {
                        foreach($options as $o) {
                            $heading_columns_of_excel[] = $quote_heading->display_name.' '.$o->display_name;    
                        }
                    }
                }
            }

            $data['data'][] = $heading_columns_of_excel; 
            foreach($company as $c) {
                $temp = [$c->id, $c->company_name];
                foreach($quote_headings as $quote_heading) {
                    if(isset($excel_data[$c->id][$quote_heading->id])) {
                        $temp[] = $excel_data[$c->id][$quote_heading->id]->value;
                        $option_values = json_decode($excel_data[$c->id][$quote_heading->id]);
                    }

                    if(!is_null($quote_heading->options)) {
                        $options = json_decode($quote_heading->options);
                        if(!is_null($options)) {
                            
                            foreach($options as $o) {
                                $display_name = $o->display_name;
                                $temp[] = isset($option_values->$display_name) ? $option_values->display_name : NULL;
                            }
                        }
                    }
                }

                $data['data'][] = $temp;

            }

            $spreadsheet[] = $data;    
        }

        

        (new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Quote');   
    }

    public function getUploadBalanceSheet($company_id) {
        $view = [];
        $view['fiscal_year'] = FiscalYearModel::orderBy('ordering', 'ASC')->get();
        $view['sub_fiscal_year'] = SubFiscalYearModel::orderBy('group_under', 'ASC')->orderBy('ordering', 'ASC')->get();
        $view['company_id'] = $company_id;

        return view($this->view.'upload-balance-sheet')
                ->with($view);   
    }

    public function postUploadBalanceSheet($company_id) {
        $input = request()->all();

        $start_fiscal_year_id = $input['start_fiscal_year_id'];
        $end_fiscal_year_id = $input['end_fiscal_year_id'];
        
        if($input['start_sub_year_id']) {
            $start_sub_year_id = $input['start_sub_year_id'];
            $end_sub_year_id = $input['end_sub_year_id'];
        }
 
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
            $tab_id = CompanyBalanceSheetTabsModel::where('tab_name', $tab)->firstOrFail()->id;
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

                            $record = CompanyBalanceSheetModel::firstOrNew([
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

        session()->flash('success-msg', 'Balance Sheet successfully updated');
        \DB::commit();
        return redirect()->back();
    }

    public function postUploadBalanceSheetHeadings($company_id) {
        $input = request()->all();
        $fiscal_year_id = $input['fiscal_year_id'];
        $sub_year_id = $input['sub_year_id'];
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);

        \DB::beginTransaction();

        foreach($data as $tab => $sheet) {
            $tab_id = CompanyBalanceSheetTabsModel::where('tab_name', $tab)->firstOrFail()->id;
            foreach($sheet as $row) {
                if($row['ID']) {
                    $balance_sheet_heading = CompanyBalanceSheetHeadingsModel::where(['id' => $row['ID']])->firstOrFail();    
                    $balance_sheet_heading->heading = $row['Particular'];
                    $balance_sheet_heading->tab_id = $tab_id;
                    $balance_sheet_heading->sector_id = $company->sector_id;
                    $balance_sheet_heading->ordering = isset($row['ordering']) ? $row['ordering'] : $balance_sheet_heading->ordering;
                    //$balance_sheet_heading->parent_id = isset($row['parent_id']) ? $row['parent_id'] : NULL;
                    $balance_sheet_heading->has_value = isset($row['has_value']) ? $row['has_value'] : $balance_sheet_heading->has_value;
                    $balance_sheet_heading->show_in_summary = isset($row['show_in_summary']) ? $row['show_in_summary'] : $balance_sheet_heading->show_in_summary;
                    $balance_sheet_heading->style = isset($row['style']) ? $row['style'] : $balance_sheet_heading->style;
                    $balance_sheet_heading->save();
                } else {
                    $data_to_store = [];
                    $data_to_store['heading'] = $row['Particular'];
                    $data_to_store['tab_id'] = $tab_id;
                    $data_to_store['sector_id'] = $company->sector_id;
                    $data_to_store['ordering'] = $row['ordering'];
                    $data_to_store['has_value'] = $row['has_value'];
                    $data_to_store['show_in_summary'] = $row['show_in_summary'];
                    $data_to_store['style'] = $row['style'];
                    $balance_sheet_heading = CompanyBalanceSheetHeadingsModel::create($data_to_store);
                }
            }
        }

        session()->flash('success-msg', 'Balance Sheet successfully updated');
        \DB::commit();

        return redirect()->back();
    }

    public function downloadUploadBalanceSheet($company_id) {

        $input = request()->all();

        $download_balance_sheet_rule = (new CompanyBalanceSheetModel)->getDownloadBalanceSheetRule();
        $validator = \Validator::make($input, $download_balance_sheet_rule);

        if ($validator->fails()){

            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        $company = CompanyModel::where('id', $company_id)->firstOrFail();

        if ($company->getSector->has_child_tab == 'no')
            $tabs = CompanyBalanceSheetTabsModel::where('is_parent', 'yes')->get();
        else
            $tabs = CompanyBalanceSheetTabsModel::whereNull('is_nfrs')->get();

        $_balance_sheet_headings = CompanyBalanceSheetHeadingsModel::where('sector_id', $company->sector_id)
                                                                    ->where('has_value', 'yes')
                                                                    ->orderBy('ordering', 'ASC')
                                                                    ->get();


        $balance_sheet_headings = [];
        foreach($_balance_sheet_headings as $b) {
            $balance_sheet_headings[$b->tab_id][] = $b;
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
        
        $_company_balance_sheet = CompanyBalanceSheetModel::whereIn('fiscal_year_id', array_keys($fiscal_years));


        if(count($sub_years)) {
            $_company_balance_sheet = $_company_balance_sheet->whereIn('sub_year_id', array_keys($sub_years));
        } else {
            $_company_balance_sheet = $_company_balance_sheet->whereNull('sub_year_id');
        }

        $_company_balance_sheet = $_company_balance_sheet->where('company_id', $company_id)->get();

        $company_balance_sheet = [];
        foreach($_company_balance_sheet as $v) {
            $year_index = '';
            $year_index = isset($fiscal_years[$v->fiscal_year_id]) ? $fiscal_years[$v->fiscal_year_id].'#' : '';
            $year_index = isset($sub_years[$v->sub_year_id]) ? $year_index.$sub_years[$v->sub_year_id] : $year_index;
            $company_balance_sheet[$v->heading_id][$year_index] = $v->value;
        }

        $spreadsheet = [];
        foreach($tabs as $tab) {
            $data = ['title' => $tab->tab_name, 'data' => [], 'styles' => []];

            $data['data'][] = ['ID', 'Particular'];
            $data['styles'][] = 'bold';

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

            if(isset($balance_sheet_headings[$tab->id])) {

                foreach($balance_sheet_headings[$tab->id] as $balance_sheet_heading) {
                        $temp = [$balance_sheet_heading->id, $balance_sheet_heading->heading];

                        foreach($data['data'][0] as $index => $years) {
                            if($index < 2)
                                continue;

                            $temp[] = isset($company_balance_sheet[$balance_sheet_heading->id][$years]) ? $company_balance_sheet[$balance_sheet_heading->id][$years] : '-';
                        }
                        

                        $data['data'][] = $temp;
                        $data['styles'][] = [$balance_sheet_heading->style];

                    }
                }
                $spreadsheet[] = $data;

        }
        
        (new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Balance-Sheet');
    }

    public function getUploadQuote() {
        return view($this->view.'upload-quote');   
    }

    public function getUploadFinancials() {
        return view($this->view.'upload-financials');   
    }

    public function downloadFinancialsUpload() {

        $financial_tabs = CompanyFinancialsTabsModel::get();
        $company = CompanyModel::get();
        $_financial_headings = CompanyFinancialsHeadingsModel::get();
        $financial_headings = [];
        foreach($_financial_headings as $f) {
            $financial_headings[$f->tab_id][] = $f;
        }
        $_company_financials_headings = CompanyFinancialsModel::get();
        $company_financials_headings = [];
        foreach($_company_financials_headings as $c) {
            $company_financials_headings[$c->company_id][$c->heading_id] = $c;
        }

        $_company_as_of = CompanyFinancialsAsOfModel::get();
        $company_as_of = [];
        foreach($_company_as_of as $c) {
            $company_as_of[$c->tab_id][$c->company_id] = $c;
        }

        $spreadsheet = [];
        

        foreach($financial_tabs as $tab) {
            $data = ["title" => $tab->tab_name, "data" => []];
            $heading_columns_of_excel = ['ID', 'Company Name', 'As Of'];
            if(isset($financial_headings[$tab->id])) {
                foreach($financial_headings[$tab->id] as $tab_id => $heading) {
                    
                        $heading_columns_of_excel[] = $heading->heading;
                    
                }
            }

            $data['data'][] = $heading_columns_of_excel;
            foreach($company as $c) {
                $data_columns_of_excel = [$c->id, $c->company_name];
                $data_columns_of_excel[] = isset($company_as_of[$tab->id][$c->id]) ? $company_as_of[$tab->id][$c->id]->as_of : "-";
                foreach($financial_headings[$tab->id] as $tab_id => $heading) {
                    
                       
                        $data_columns_of_excel[] = isset($company_financials_headings[$c->id][$heading->id]) ? $company_financials_headings[$c->id][$heading->id]->value : "-";
                    
                }
                $data['data'][] = $data_columns_of_excel;
            }

            $spreadsheet[] = $data;
            $data = NULL;
        }

        (new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Financials');

    }

    public function postUploadFinancials() {
        $input = request()->all();
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);
        $_financial_tabs = CompanyFinancialsTabsModel::get();
        $financial_tabs = [];
        foreach($_financial_tabs as $f) {
            $financial_tabs[$f->tab_name] = $f->id;
        }

        $_financial_headings = CompanyFinancialsHeadingsModel::get();
        $financial_headings = [];
        foreach($_financial_headings as $f) {
            $financial_headings[$f->heading] = $f;
        }

        \DB::beginTransaction();
        foreach ($data as $tab => $sheet) {

            foreach($sheet as $rows) {
                foreach($rows as $heading => $r) {
                    if(in_array($heading, ['ID', 'Company Name'])) {
                        continue;
                    }

                    if($heading == 'As Of') {
                        if(isset($financial_tabs[$tab])) {
                            $record = CompanyFinancialsAsOfModel::firstOrNew([
                                "company_id"    =>  $rows['ID'],
                                "tab_id"    =>  $financial_tabs[$tab]
                            ]);
                            $record->as_of = $rows['As Of'];
                            $record->save();
                        }
                        continue;
                    }

                    $record = CompanyFinancialsModel::firstOrNew([
                        'company_id' => $rows['ID'],
                        'heading_id'    =>  $financial_headings[$heading]->id
                    ]);

                    $record->value = $r;
                    $record->save();
                }
            }
        }

        session()->flash('success-msg', 'Financials successfully uploaded');
        \DB::commit();

        return redirect()->back();
    }

    public function postUploadQuote() {
        $input = request()->all();
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);
       
        $_quotes = CompanyQuoteModel::get();
        $quotes = [];
        foreach($_quotes as $q) {
            $quotes[$q->tab_id][$q->display_name] = $q;
        }

        \DB::beginTransaction();
        foreach($data as $tab => $sheet) {
            $tab_id = CompanyQuoteTabModel::where('tab_name', $tab)->firstOrFail()->id;
            foreach($sheet as $rows) {
                foreach($rows as $quote_values_and_options => $r) {
                    if(in_array($quote_values_and_options, ['ID', 'Company Name'])) {
                        continue;
                    }

                    $data_to_store = [];
                    
                    if(isset($quotes[$tab_id][$quote_values_and_options])) {


                        $data_to_store['quote_id'] = $quotes[$tab_id][$quote_values_and_options]->id;
                        $data_to_store['value'] = strlen($r) ? $r : '-';
                        $data_to_store['option_value'] = NULL;
                        $options = json_decode($quotes[$tab_id][$quote_values_and_options]->options);
                        if($options) {
                            $temp = [];
                            foreach($options as $o) {
                                
                                if(isset($rows[$quote_values_and_options.' '.$o->display_name])) {
                                    
                                    $temp[] = [$o->display_name => $rows[$quote_values_and_options.' '.$o->display_name]];
                                    unset($rows[$quote_values_and_options.' '.$o->display_name]);
                                }
                            }

                            $data_to_store['option_value'] = count($temp) ? json_encode($temp) : NULL;
                        }

                        $record = CompanyQuoteValuesModel::firstOrNew([
                            'company_id'    =>  $rows['ID'],
                            'quote_id'  =>  $data_to_store['quote_id']
                        ]);

                        $record->value = $data_to_store['value'];
                        $record->option_value = $data_to_store['option_value'];
                        $record->save();                        
                    }
                }
                
            }
        }

        session()->flash('success-msg', 'Quotes successfully updated');
        \DB::commit();


        return redirect()->back();
    }

    /// for company summary ///

    public function getSummaryListView($company_id) {
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $data = CompanySummaryModel::where('company_id', $company_id)->orderBy('id', 'DESC')->paginate(20);
        return view($this->view.'summary-list')
                ->with('data', $data)
                ->with('company', $company);
    }

    public function getSummaryCreateView($company_id) {
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        return view($this->view.'summary-create')
                ->with('company', $company);
                
    }

    public function postSummaryCreateView($company_id) {
        $data = request()->all();
        $data['data']['company_id'] = $company_id;

        $validator = \Validator::make($data['data'], (new CompanySummaryModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
           if($data['data']['asset_type'] == 'image') {
                request()->file('data.asset')->store('company-summary');
                $data['data']['asset'] = request()->file('data.asset')->hashName();
            }
        }

        $news_id = CompanySummaryModel::create($data['data'])->id;

        \Session::flash('success-msg', 'Company Summary successfully created');

        return redirect()->back();
    }

    public function getSummaryEditView($company_id, $id) {
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $data = CompanySummaryModel::where('id', $id)->firstOrFail();

        return view($this->view.'summary-edit')
                ->with('data', $data)
                ->with('company', $company);

    }

    public function postSummaryEditView($company_id, $id) {
        $original_data = CompanySummaryModel::where('id', $id)->firstOrFail();
        $news_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new CompanySummaryModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {

            if($data['data']['asset_type'] == 'image' && request()->hasFile('data.asset')) {
                request()->file('data.asset')->store('company-summary');
                $data['data']['asset'] = request()->file('data.asset')->hashName();
            } else {
                $data['data']['asset'] = $original_data->asset;
            }
            
            CompanySummaryModel::where('id', $id)->update($data['data']);

            \Session::flash('success-msg', 'Company successfully updated');

            return redirect()->back();
        }
    }

    public function postSummaryDeleteView($company_id, $id) {
        
        $response = $this->apiSummaryDelete($id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-error-msg']);
        }

        return redirect()->back();
    }

    public function postSummaryDeleteMultipleView($company_id) {
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiSummaryDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiSummaryDelete($id) {
        try {
            $data = CompanySummaryModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete('company-summary'.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-error-msg' => 'Company could not be deleted'];
        }

        return ['status' => true, 'message' => 'Company successfully deleted'];
        
    }

    /// for company summary ///


    public function getUploadDividend($company_id){
        $view = [];
        $view['fiscal_year'] = FiscalYearModel::orderBy('ordering', 'ASC')->get();
        $view['sub_fiscal_year'] = SubFiscalYearModel::orderBy('group_under', 'ASC')->orderBy('ordering', 'ASC')->get();
        $view['company_id'] = $company_id;

        return view($this->view.'upload-dividend')
                ->with($view);        
    }

    public function downloadUploadDividend($company_id){
        $input = request()->all();

        $download_dividend_rule = (new DividendModel)->getDownloadDividendRule();
        $validator = \Validator::make($input, $download_dividend_rule);

        if ($validator->fails()){

            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $type = $company->dividendType->type;
        if ($type == 'Dividend')
            $tabs = ['Dividend', 'Details'];
        else if ($type == 'Right Share')
            $tabs = ['Right Share'];
        
        $headings = DividendColumnModel::where('type_id', $company->dividend_type_id)->get();

        $dividend_details = DividendDetailModel::where('company_id', $company_id)->orderBy('book_closure_date', 'DESC')->get();

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

        $_company_dividends = DividendModel::whereIn('fiscal_year_id', array_keys($fiscal_years))->where('company_id', $company_id)->get();

        $company_dividends = [];
        foreach($_company_dividends as $v) {
            $year_index = '';
            $year_index = isset($fiscal_years[$v->fiscal_year_id]) ? $fiscal_years[$v->fiscal_year_id] : '';
            $company_dividends[$v->column_id][$year_index] = $v->value;
        }

        $spreadsheet = [];
        foreach($tabs as $tab) {
            $data = ['title' => $tab, 'data' => []];
            if ($tab == 'Details'){
                $data['data'][]  = ['ID', 'Fiscal Year', 'Bonus Share', 'Cash Dividend', 'Total Dividend', 'Bookclose Date'];
                $data['styles'][] = 'bold';

                foreach ($dividend_details as $d){
                    $data['data'][] = [$d->id, $d->fiscal_year, $d->bonus_share, $d->cash_dividend, $d->total_dividend, $d->book_closure_date];
                }
            }else {
                $data['data'][] = ['ID', 'Particular'];
                $data['styles'][] = 'bold';

                foreach ($fiscal_years as $fiscal_year_title){
                    $data['data'][0][] = $fiscal_year_title;
                }
    
                foreach($headings as $heading) {
                    $temp = [$heading->id, $heading->column_name];

                    foreach($data['data'][0] as $index => $years) {
                        if($index < 2)
                            continue;
    
                        $temp[] = isset($company_dividends[$heading->id][$years]) ? $company_dividends[$heading->id][$years] : '-';
                    }

                    $data['data'][] = $temp;
//                  $data['styles'][] = '';

                }
            }
            $spreadsheet[] = $data;
        }
        (new \App\Http\Controllers\ExcelController)->apiDownloadExcel($spreadsheet, $filename='Dividend');        
    }

    public function postUploadDividend($company_id){
        $input = request()->all();

        $start_fiscal_year_id = $input['start_fiscal_year_id'];
        $end_fiscal_year_id = $input['end_fiscal_year_id'];
 
        $data = (new \App\Http\Controllers\ExcelController)->returnData($input['data']['excel_file']);

        // dd($data);

        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $dividend_type = isset($company->dividendType)? $company->dividendType->type : NULL;

        $all_years = [];
        foreach(FiscalYearModel::select('id', 'fiscal_year')->orderBy('id')->get() as $_year){
                            
            $all_years[$_year->fiscal_year] = $_year->id;
        }

        \DB::beginTransaction();
        foreach($data as $tab => $sheet) {
            if ($tab == 'Details'){
                foreach ($sheet as $row){
                    if (is_null($row['Bookclose Date']))
                        continue;
                    $row['company_id'] = $company_id;
                    if (!is_null($row['ID'])){
                        $record = DividendDetailModel::where('id', $row['ID'])->first();

                        if(intval($row['Bookclose Date']) > 2100 ){
                            $str = intval($row['Bookclose Date']);
                        }
                        $dt = Carbon::create(1899, 12, 30, 0);
                        $dt->addDays($str);
                        $dt = $dt->format('Y-m-d');
                        
                        $record->update([
                            'book_closure_date' => $dt,
                            'total_dividend' => $row['Total Dividend'],
                            'bonus_share' => $row['Bonus Share'],
                            'cash_dividend' => $row['Cash Dividend'],
                            'fiscal_year'   =>  $row['Fiscal Year']
                        ]);
                    }
                    else{
                        if(intval($row['Bookclose Date']) > 2100 ){
                            $str = intval($row['Bookclose Date']);
                        }
                        $dt = Carbon::create(1899, 12, 30, 0);
                        $dt->addDays($str);
                        $dt = $dt->format('Y-m-d');


                        $record = DividendDetailModel::create([
                            'company_id' => $row['company_id'],
                            'book_closure_date' => $dt,
                            'total_dividend' => $row['Total Dividend'],
                            'bonus_share' => $row['Bonus Share'],
                            'cash_dividend' => $row['Cash Dividend'],
                            'fiscal_year'   =>  $row['Fiscal Year']
                        ]);
                    }
                }
            }

            foreach($sheet as $row) {
                if($row['ID']) {
                    foreach ($row as $row_heading => $value){

                        if(in_array($row_heading, ['ID', 'Particular']))
                            continue;

                        if (isset($all_years[$row_heading])) {
                            
                            $fiscal_year_id = $all_years[$row_heading];

                            $record = DividendModel::firstOrNew([
                                'column_id' => $row['ID'],
                                'company_id'    =>  $company_id,
                                'fiscal_year_id'    =>  $fiscal_year_id,
                            ]);
                            $record->value = $value;
                            $record->save();
                        }
                    }
                }
            }
        }

        session()->flash('success-msg', 'Dividend successfully updated');
        \DB::commit();
        return redirect()->back();        
    }

    public function getDividendDetailsListView($company_id){
        $data = DividendDetailModel::where('company_id', $company_id)->orderBy('book_closure_date', 'DESC')->paginate(20);
        $company = CompanyModel::where('id', $company_id)->first()->company_name;

        return view($this->view.'dividend-details-list')
                ->with('data', $data)
                ->with('company', $company);
    }

    public function postDividendDetailDeleteView($dividend_detail_id) {
        
        $response = $this->apiDividendDetailDelete($dividend_detail_id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-error-msg']);
        }

        return redirect()->back();
    }

    public function postDividendDetailDeleteMultipleView() {
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDividendDetailDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDividendDetailDelete($id) {
        try {
            $data = DividendDetailModel::where('id', $id)->firstOrFail();
            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-error-msg' => 'Dividend Detail could not be deleted'];
        }

        return ['status' => true, 'message' => 'Dividend detail successfully deleted'];
        
    }

    public function getFairValueView($company_id){
        $company = CompanyModel::where('id', $company_id)->with('expert')->firstOrFail();
        $experts = ExpertsModel::orderBy('ordering', 'ASC')->get();

        return view($this->view.'fair-value')
                    ->with('company', $company)
                    ->with('experts', $experts);
    }

    public function postFairValueRatingUpdate($company_id){
        $input = request()->all();
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        //$input['data']['rating_updated_at'] = \Carbon\Carbon::Now();
        $company->update($input['data']);

        \Session::flash('success-msg', 'Rating updated successfully');
        return redirect()->back();
    }

    public function postExpertUpdate($company_id){
        $input = request()->all();
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $company->update($input['data']);

        \Session::flash('success-msg', 'Expert updated successfully');
        return redirect()->back();
    }
    public function getQuoteHeadingsView(){
        $tabs = CompanyQuoteTabModel::get();

        return view($this->view.'quote-tab-list')
                ->with('tabs', $tabs);
    }

    public function getQuoteHeadingsListView($tab_id){
        $quotes = isset($tab_id)?CompanyQuoteModel::with('tab')->orderBy('ordering', 'ASC')->where('tab_id',$tab_id)->get() : Null;
        $tabs = CompanyQuoteTabModel::get();
        $selected_tab = isset($tab_id)?CompanyQuoteTabModel::where('id', $tab_id):Null;

        return view($this->view.'quotes-list')
                    ->with('quotes', $quotes)
                    ->with('tabs', $tabs)
                    ->with('selected_tab', $selected_tab);
    }

    public function postQuoteHeadingsCreateView(){
        $data =  request()->all();

        $validator = \Validator::make($data['data'], (new CompanyQuoteModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $data['data']['options'] = 'random string';
            CompanyQuoteModel::create($data['data']);
            \Session::flash('success-msg', 'Qoute heading successfully created');

            return redirect()->back();
        }
    }

    public function postQuoteHeadingsEditView($quote_id){
        $data =  request()->all();

        $validator = \Validator::make($data['data'], (new CompanyQuoteModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            CompanyQuoteModel::where('id',$quote_id)->update($data['data']);
            \Session::flash('success-msg', 'Qoute heading successfully updated');

            return redirect()->back();
        }
    }
    public function postQouteHeadingsDeleteView($quote_id){
        $quote = CompanyQuoteModel::where('id', $quote_id)->firstOrFail();
        $quote->delete();

        \Session::flash('success-msg', 'Qoute heading successfully removed');
        return redirect()->back();
    }

    public function postQuoteHeadingsMultipleDeleteView(){
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiQuoteHeadingsDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiQuoteHeadingsDelete($id) {
        try {
            $data = CompanyQuoteModel::where('id', $id)->firstOrFail();
            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-error-msg' => 'Quote heading could not be deleted'];
        }

        return ['status' => true, 'message' => 'Quote heading successfully deleted'];
        
    }

    public function getFinancialTabsHeadingsListView($company_id, $tab_id){
        $headings = CompanyHeadingsModel::where('company_id', $company_id)->with('heading.tab')->get();
        $sector = CompanyModel::where('id', $company_id)->first()->getSector;
        $tabs = BalanceSheetSectorTabsModel::where('sector_id', $sector->id)->with('tab')->get();
        return view($this->view.'list-headings')
                ->with('data', $headings)
                ->with('company_id', $company_id)
                ->with('tab_id', $tab_id)
                ->with('tabs', $tabs);
    }

    public function postFinancialTabsHeadingsRefresh($company_id){
        $companies = CompanyModel::where('id', $company_id)->first();
        try {
            $sector = $companies->getSector;
            $headings = CompanyBalanceSheetHeadingsModel::where('sector_id', $sector->id)->get();
            foreach($headings as $h){
                $row = CompanyHeadingsModel::firstOrNew(['company_id' => $companies->id, 'heading_id' => $h->id]);
                $row->is_linked = isset($row->is_linked) ? $row->is_linked : 'yes';
                $row->save();
            }
            \Session::flash('success-msg', 'Refreshed!');
        }catch(\Exception $e){
            \Session::flash('friendly-error-msg', $e->getMessage());
        }

        return redirect()->back();
    }

    public function postFinancialTabsHeadingsListView(){
        $rid = request()->get('rid');

        try {
            if(!empty($rid)){
                \DB::beginTransaction();
                foreach($rid as $r){
                    $company_header = CompanyHeadingsModel::where('id', $r)->first();
                    $company_header->is_linked = $company_header->is_linked == 'yes' ? 'no' : 'yes';
                    $company_header->save();
                }
                \DB::commit();
                \Session::flash('success-msg', 'Linked status changed successfully');
            }else{
                \Session::flash('success-msg', 'No link selected');
            }
        }
        catch(Exception $e){
            \Session::flash('friendly-error-msg', $e->getMessage());
        }
        return redirect()->back();
    }
}
