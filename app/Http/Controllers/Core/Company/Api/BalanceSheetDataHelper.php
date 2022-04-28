<?php

namespace App\Http\Controllers\Core\Company\Api;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetHeadingsModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetTabsModel;
use App\Http\Controllers\Core\Company\FiscalYearModel;
use App\Http\Controllers\Core\Company\CompanyHeadingsModel;
use App\Http\Controllers\Core\Company\SubFiscalYearModel;
use App\Http\Controllers\Core\FiscalYear\CompanyFiscalYearModel;
use App\Http\Controllers\Core\Performance\PerformanceDataModel;
use App\Http\Controllers\Core\Performance\PerformanceModel;
use App\Http\Controllers\Core\Valuation\ValuationDataModel;
use App\Http\Controllers\Core\Valuation\ValuationModel;
use Illuminate\Support\Str;

class BalanceSheetDataHelper {
    public function getData($company, $type, $show_only_summary=true, $no_of_years=0, $blurred_part, $tab_id=0) {
        $company_id = $company->id;
        $sector_id = $company->sector_id;

        $_historical_tab_ids = (new ApiCompanyController)->checkSectorHasHistoricalTabs($sector_id);
        $historical_tabs = [];
        foreach($_historical_tab_ids['data'] as $d) {
            $historical_tabs[] = $d->tab_id;
        }



        if($no_of_years != 0) {
            $no_of_years = $type == 'quarterly' ? ceil($no_of_years / 4) : $no_of_years;
        }

        $tabs = $tab_id ? CompanyBalanceSheetTabsModel::where('id', $tab_id)->get() : CompanyBalanceSheetTabsModel::whereNull('parent_id')->orWhereIn('id', $historical_tabs)->get();

        $_headings_ids = CompanyHeadingsModel::where('company_id', $company_id)->where('is_linked', 'yes')->pluck('heading_id');

        $_headings = CompanyBalanceSheetHeadingsModel::whereIn('id', $_headings_ids)
                                                        ->orderBy('ordering', 'ASC');
        if($show_only_summary) {
            $_headings = $_headings->where(function($query) {
                return $query->where('show_in_summary', 'yes')
                        ->orWhere('in_graph', 'yes');
            });
        }

        $_headings= $_headings->get();

        $headings = [];
        foreach($_headings as $h){
            $headings[$h->tab_id][] = $h;
        }

        $company_years_table = (new CompanyFiscalYearModel)->getTable(); 
        $years_table = (new FiscalYearModel)->getTable();
        $_fiscal_years = FiscalYearModel::join($company_years_table, $company_years_table.'.fiscal_year_id', '=',  $years_table.'.id')->where('company_id', $company_id)->select($years_table.'.*')->orderBy('ordering', 'DESC');
        if($no_of_years) {
            $_all_years = $_fiscal_years->pluck('fiscal_year');
            $_fiscal_years = $_fiscal_years->take($no_of_years);
        }
        $_fiscal_years = $_fiscal_years->get();

        $fiscal_years = [];
        
        $sub_fiscal_years = [];
        foreach($_fiscal_years as $f) {
            $fiscal_years[$f->id] = $f->fiscal_year;
        }
        $_data = CompanyBalanceSheetModel::where('company_id', $company_id)
                                        ->whereIn('fiscal_year_id', array_keys($fiscal_years));

        if($type == 'quarterly') {
            $_sub_fiscal_years = SubFiscalYearModel::where('group_under', 1)->orderBy('ordering', 'ASC')->get();
            $quarters = $_sub_fiscal_years->pluck('title')->toArray();
            foreach($_sub_fiscal_years as $sub_fiscal_year) {
                $sub_fiscal_years[$sub_fiscal_year->id] = $sub_fiscal_year->title;
            }

            $_fiscal_years = [];
            foreach($fiscal_years as $fiscal_year_id => $values) {
                foreach($sub_fiscal_years as $sub_fiscal_year_id => $sub_fiscal_year) {
                    $_fiscal_years[$fiscal_year_id.'-'.$sub_fiscal_year_id] = $values.' '.$sub_fiscal_year;
                }
            }
            $fiscal_years = $_fiscal_years;

            $_data = $_data->whereIn('sub_year_id', array_keys($sub_fiscal_years));
        } else {
            $_data = $_data->whereNull('sub_year_id');  
            $_fiscal_years = [];  
            foreach($fiscal_years as $fiscal_year_id => $values) {
                $_fiscal_years[$fiscal_year_id.'-'] = $values;
            }
            $fiscal_years = $_fiscal_years;
        }

        $_data = $_data->get();
        
        $data = [];
        
        foreach($_data as $d) {
            $data[$d->fiscal_year_id.'-'.$d->sub_year_id][$d->heading_id] = $d;
        }

       
        $_return_data = [];


        foreach($tabs as $t) {
            $_return_data[$t->id]['tab_name'] = $t->tab_name;
            $_return_data[$t->id]['is_permanent'] = $t->permanent;
            $_return_data[$t->id]['data'] = [];
            foreach($fiscal_years as $fiscal_year_id => $f) {
                if(isset($headings[$t->id])) {
                    foreach($headings[$t->id] as $index => $h) {
                        $_return_data[$t->id]['data'][$fiscal_year_id][$h->id] = ['heading_name' => $h->heading, 'value' => '-'];
                        if(isset($data[$fiscal_year_id][$h->id])) {
                            $_return_data[$t->id]['data'][$fiscal_year_id][$h->id]['value'] = $data[$fiscal_year_id][$h->id]->value;
                        }
                    }
                }
            }
        }

        $return_data = [];
        foreach($_return_data as $tab_id => $r) {
            $temp = ['tab_name' => $r['tab_name'],
                     'tab_id'   =>  $tab_id,
                     'is_permanent' =>  $r['is_permanent'],
                            'table'   => []];
            $temp_header = [];
            $historical = CompanyBalanceSheetTabsModel::where('id',$tab_id)->first()->historical;
            foreach (array_values($fiscal_years) as $year){
                $temp_header[] = [
                    'key' => $year,
                    'alias' => $year,
                    'historical' => $historical?true:false 
                ];

                $historical = Null;
             }
            $temp['table']['headers'] = array_merge([["key"=>"e","alias"=>"", "historical"=>Null]], $temp_header);

            $temp['table']['body'] = [];

            if(in_array($tab_id, array_keys($headings))){
                foreach($headings[$tab_id] as $h) {
                    $temp_row = [];
                    $temp_row['row_style'] = ($h->style) ? $h->style: Null;
                    $temp_row['row'] = [];
                    foreach($temp['table']['headers'] as $header){
                        if($header['key'] == 'e'){
                            $value = $h->heading;
                        }
                        else{
                            foreach($fiscal_years as $fiscal_year_id => $f) {
                                if ($f == $header['key']){
                                    $value = $r['data'][$fiscal_year_id][$h->id]['value'];
                                    break;
                                }
                            }
                        }

                        $temp_row['row'][] = [$header['key']  => [
                                                "key" => $header['key'],
                                                "value" => isset($value)?$value:'-',
                        ]];
                        $value = $historical = Null;
                    }
                    $temp['table']['body'][] = $temp_row;
                }

                $temp['graph']['graph_data'] = [];
                $temp['graph']['fields'] = [];
                                    //     "graph_field" => [/*'Assets', 'Debts'*/],
                                    //     "line_fields" => ['Assets/Debts']
                                    // ];

                foreach (array_slice($fiscal_years, 0, 3) as $fiscal_year_id => $fiscal_year){
                    $_temp = ["year" => $fiscal_year];

                    foreach($headings[$tab_id] as $h) {
                        if($h->in_graph == 'yes') {
                        
                            // if(!in_array($h->heading, $temp['graph']['fields']['graph_field'])) {
                            if(!in_array($h->heading, array_map(function($e) {
                                return is_object($e) ? $e->name : $e['name'];
                            }, $temp['graph']['fields']))){
                                
                                $yo = [
                                    'dataKey' => Str::snake($h->heading),
                                    'name' => $h->heading,
                                    'fill' => "red"
                                ];

                                $temp['graph']['fields'][] = $yo;
                            }
                            $_temp[Str::snake($h->heading)] = isset($r['data'][$fiscal_year_id][$h->id]['value']) ? (int) $r['data'][$fiscal_year_id][$h->id]['value'] : 0;
                        }
                    }
                    $temp['graph']['graph_data'][] = $_temp;
                }                      
            }

            $return_data[] = $temp;
        }
        if (count($return_data) == 1) {
            $return_data = $return_data[0];
        }
        if($blurred_part){
            $n = 0;
            if($type == 'quarterly'){
                $last_array = end($return_data['table']['headers'])['key'];
                $last_year = explode(' ', $last_array);
                $_all_years = $_all_years->toArray();
                $array_index = array_search($last_year[0], $_all_years);
                $_years_used = [$_all_years[$array_index+1], $_all_years[$array_index+2]];
                foreach($_years_used as $y){
                    foreach($quarters as $quarter){
                        if($n == 5){
                            break;
                        }
                        $return_data['table']['headers'][] = [
                            "key" => $y.' '.$quarter,
                            "alias" => $y.' '.$quarter,
                            "historical" => "blurred"
                        ];
                        $n = $n+1;
                    }
                }
            }else{
                foreach($_all_years as $y){
                    if($n == 5){
                        break;
                    }
                    if (!in_array($y, array_column($return_data['table']['headers'], 'key'))){
                        $return_data['table']['headers'][] = [
                            "key" => $y,
                            "alias" => $y,
                            "historical" => "blurred"
                        ];
                        $n = $n+1; 
                    }
                }    
            }
        }
        return $return_data;
    }


    public function approx($n) {
        // first strip any formatting;
        // $n = (0+str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) 
            return false;

        // now filter it;
        if ($n > 1000000000000)
            return round(($n/1000000000000), 2).' T';
        elseif ($n > 1000000000)
            return round(($n/1000000000), 2).' B';
        elseif ($n > 1000000)
            return round(($n/1000000), 2).' M';
        elseif
            ($n > 1000) return round(($n/1000), 2).' K';

        return number_format($n);
    }
    public function getHelperData($company, $type, $show_only_summary=true, $no_of_years=0, $heading_model, $data_model, $blurred_part=false) {
        $company_id = $company->id;
        $sector_id = $company->sector_id;

        if($no_of_years != 0) {
            $no_of_years = $type == 'quarterly' ? ceil($no_of_years / 4) : $no_of_years;
        }


        $headings = $heading_model::where('sector_id', $sector_id)
                                                        ->orderBy('ordering', 'ASC');
        if($show_only_summary) {
            $headings = $headings->where('show_in_summary', 'yes')
                                    ->orWhere('in_graph', 'yes');
        }

        $headings = $headings->get();

        $company_years_table = (new CompanyFiscalYearModel)->getTable(); 
        $years_table = (new FiscalYearModel)->getTable();
        $_fiscal_years = FiscalYearModel::join($company_years_table, $company_years_table.'.fiscal_year_id', '=',  $years_table.'.id')->where('company_id', $company_id)->orderBy('ordering', 'DESC')->select($years_table.'.*');
 

        if($no_of_years) {
            $_all_years = $_fiscal_years->pluck('fiscal_year');
            $_fiscal_years = $_fiscal_years->take($no_of_years);
        }
        $_fiscal_years = $_fiscal_years->get();

        $fiscal_years = [];
        
        $sub_fiscal_years = [];
        foreach($_fiscal_years as $f) {
            $fiscal_years[$f->id] = $f->fiscal_year;
        }
        $_data = $data_model::where('company_id', $company_id)
                                        ->whereIn('fiscal_year_id', array_keys($fiscal_years));

        if($type == 'quarterly') {
            $_sub_fiscal_years = SubFiscalYearModel::where('group_under', 1)->orderBy('ordering', 'ASC')->get();
            $quarters = $_sub_fiscal_years->pluck('title')->toArray();
            
            foreach($_sub_fiscal_years as $sub_fiscal_year) {
                $sub_fiscal_years[$sub_fiscal_year->id] = $sub_fiscal_year->title;
            }

            $_fiscal_years = [];
            foreach($fiscal_years as $fiscal_year_id => $value) {
                foreach($sub_fiscal_years as $sub_fiscal_year_id => $sub_fiscal_year) {
                    $_fiscal_years[$fiscal_year_id.'-'.$sub_fiscal_year_id] = $value.' '.$sub_fiscal_year;
                }
            }
            $fiscal_years = $_fiscal_years;

            $_data = $_data->whereIn('sub_year_id', array_keys($sub_fiscal_years));
        } else {
            $_data = $_data->whereNull('sub_year_id');  
            $_fiscal_years = [];  
            foreach($fiscal_years as $fiscal_year_id => $value) {
                $_fiscal_years[$fiscal_year_id.'-'] = $value;
            }
            $fiscal_years = $_fiscal_years;
        }

        $_data = $_data->get();

        $data = [];
        foreach($_data as $d) {
            $data[$d->fiscal_year_id.'-'.$d->sub_fiscal_year_id][$d->heading_id] = $d;
        }

        $_return_data = [];

        $_return_data['data'] = [];
        foreach($fiscal_years as $fiscal_year_id => $f) {
            
                foreach($headings as $index => $h) {
                    $_return_data['data'][$fiscal_year_id][$h->id] = ['heading_name' => $h->heading, 'value' => NULL];
                    if(isset($data[$fiscal_year_id][$h->id])) {
                        $_return_data['data'][$fiscal_year_id][$h->id]['value'] = $data[$fiscal_year_id][$h->id]->value;
                    }
                }
        }

        $return_data = [];

            $temp = ['table'   => []];
            $temp['table']['headers']['data'] = array_merge([''], array_values($fiscal_years));
            $temp['table']['rows']['data'] = [];
            foreach($headings as $h) {
                $temp_row[] = $h->heading;
                foreach($fiscal_years as $fiscal_year_id => $f) {
                    $temp_row[] = $_return_data['data'][$fiscal_year_id][$h->id]['value'];
                }
                $temp['table']['rows']['style'][] = ($h->style) ? explode(',', $h->style) : [];
                $temp['table']['rows']['data'][] = $temp_row;
                
                $temp_row = NULL;
            }

            $temp['table'] = \App\Http\Controllers\HelperController::antDTableFormatter($temp['table']);

            $temp['graph']['graph_data'] = [];
            $temp['graph']['fields'] = [
                                        "graph_field" => [/*'Assets', 'Debts'*/],
                                        "line_fields" => ['Assets/Debts']
                                    ]; 

            foreach ($fiscal_years as $fiscal_year_id => $fiscal_year){
                $_temp = ["label" => $fiscal_year];

                foreach($headings as $h) {
                    if($h->in_graph == 'yes') {
                        
                        if(!in_array($h->heading, $temp['graph']['fields']['graph_field'])) {
                            $temp['graph']['fields']['graph_field'][] = $h->heading;
                        }
                        $_temp[$h->heading] = isset($r['data'][$fiscal_year_id][$h->id]['value']) ? (int) $r['data'][$fiscal_year_id][$h->id]['value'] : 0;
                    }
                }
                

                $temp['graph']['graph_data'][] = $_temp;
            }
            $return_data = $temp;

            if($blurred_part){
                $n = 0;
                if($type == 'quarterly'){
                    $last_array = end($return_data['table']['headers']['columns'])['data'];
                    $last_year = explode(' ', $last_array);
                    $_all_years = $_all_years->toArray();
                    $array_index = array_search($last_year[0], $_all_years);
                    $_years_used = [$_all_years[$array_index+1], $_all_years[$array_index+2]];
                    foreach($_years_used as $y){
                        foreach($quarters as $quarter){
                            if($n == 5){
                                break;
                            }
                            $return_data['table']['headers']['columns'][] = [
                                "style" => "blurred",
                                "data" => $y.' '.$quarter,
                            ];
                            $n = $n+1;
                        }
                    }
                }else{
                    foreach($_all_years as $y){
                        if($n == 5){
                            break;
                        }
                        if (!in_array($y, array_column($return_data['table']['headers']['columns'], 'data'))){
                            $return_data['table']['headers']['columns'][] = [
                                "style" => "blurred",
                                "data" => $y
                            ];
                            $n = $n+1; 
                        }
                    }    
                }
            }
        return $return_data;
    }

    public function getValuationData($company, $type, $show_only_summary=true, $no_of_years=0, $blurred_part) {

        return $this->getHelperData($company, $type, $show_only_summary, $no_of_years, ValuationModel::class, ValuationDataModel::class, $blurred_part);
        
    }

    public function getPerformanceData($company, $type, $show_only_summary=true, $no_of_years=0){
        $return_data = [
            'header' => [],
            'data'  =>  []
        ];


        $company_id = $company->id;
        $sector_id = $company->sector_id;

        if($no_of_years != 0) {
            $no_of_years = $type == 'quarterly' ? ceil($no_of_years / 4) : $no_of_years;
        }


        $headings = PerformanceModel::where('sector_id', $sector_id)
                                    ->with('subheadings')
                                    ->whereNull('parent_id')
                                    ->orderBy('ordering', 'ASC');
        
        $headings = $headings->get();

        $company_years_table = (new CompanyFiscalYearModel)->getTable(); 
        $years_table = (new FiscalYearModel)->getTable();
        $_fiscal_years = FiscalYearModel::join($company_years_table, $company_years_table.'.fiscal_year_id', '=',  $years_table.'.id')->where('company_id', $company_id)->orderBy('ordering', 'DESC')->select($years_table.'.*');
 

        if($no_of_years) {
            $_fiscal_years = $_fiscal_years->take($no_of_years);
        }
        $_fiscal_years = $_fiscal_years->get();

        $fiscal_years = [];
        
        $sub_fiscal_years = [];
        foreach($_fiscal_years as $f) {
            $fiscal_years[$f->id] = $f->fiscal_year;
        }
        $_data = PerformanceDataModel::where('company_id', $company_id)
                                        ->whereIn('fiscal_year_id', array_keys($fiscal_years));

        if($type == 'quarterly') {
            $_sub_fiscal_years = SubFiscalYearModel::where('group_under', 1)->orderBy('ordering', 'ASC')->get();
            foreach($_sub_fiscal_years as $sub_fiscal_year) {
                $sub_fiscal_years[$sub_fiscal_year->id] = $sub_fiscal_year->title;
            }

            $_fiscal_years = [];
            foreach($fiscal_years as $fiscal_year_id => $value) {
                foreach($sub_fiscal_years as $sub_fiscal_year_id => $sub_fiscal_year) {
                    $_fiscal_years[$fiscal_year_id.'-'.$sub_fiscal_year_id] = $value.' '.$sub_fiscal_year;
                }
            }
            $fiscal_years = $_fiscal_years;

            $_data = $_data->whereIn('sub_year_id', array_keys($sub_fiscal_years));
        } else {
            $_data = $_data->whereNull('sub_year_id');  
            $_fiscal_years = [];  
            foreach($fiscal_years as $fiscal_year_id => $value) {
                $_fiscal_years[$fiscal_year_id.'-'] = $value;
            }
            $fiscal_years = $_fiscal_years;
        }

        $_data = $_data->get();

        $data = [];
        foreach($_data as $d) {
            $data[$d->fiscal_year_id.'-'.$d->sub_fiscal_year_id][$d->heading_id] = $d;
        }

        $return_data['header'][] = [
            'alias' =>  NULL,
            'key'   =>  'heading'
        ];

        foreach($fiscal_years as $index => $f) {
            $return_data['header'][] = [
                'alias' =>  $f,
                'key'   =>  $index
            ];
        }

        foreach($headings as $h) {
            $temp['heading'] = $h->heading;

            foreach($fiscal_years as $index => $f) {
                $temp[$index] = isset($data[$index][$h->id]) ? $data[$index][$h->id]->value : '-';
            }

            if(count($h->subheadings) == 0) {
                $temp['sub_row'] = NULL;
            } else {
                $temp['sub_row'] = [];

                foreach($h->subheadings->sortBy('ordering')  as $sub) {
                   
                    $sub_row=[];
                    foreach($fiscal_years as $_index => $_f) {
                        $sub_row['heading'] = $sub->heading;
                        $sub_row[$_index] = isset($data[$_index][$sub->id]) ? number_format($data[$_index][$sub->id]->value*100, 2).'%' : '-';
                    }

                    $temp['sub_row'][] = $sub_row;
                }
            }

            $return_data['data'][] = $temp;
            $temp = [];
        }

        return $return_data;
    }


}