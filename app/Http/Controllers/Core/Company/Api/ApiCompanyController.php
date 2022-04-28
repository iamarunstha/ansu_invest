<?php

namespace App\Http\Controllers\Core\Company\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\AgmSgm\AgmSgmModel;
use App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetHeadingsModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetModel;
use App\Http\Controllers\Core\Company\CompanyBalanceSheetTabsModel;
use App\Http\Controllers\Core\Company\CompanyFinancialsAsOfModel;
use App\Http\Controllers\Core\Company\CompanyFinancialsHeadingsModel;
use App\Http\Controllers\Core\Company\CompanyFinancialsModel;
use App\Http\Controllers\Core\Company\CompanyFinancialsTabsModel;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Company\CompanyQuoteModel;
use App\Http\Controllers\Core\Company\CompanyQuoteTabModel;
use App\Http\Controllers\Core\Company\CompanyQuoteValuesModel;
use App\Http\Controllers\Core\Company\CompanySectorModel;
use App\Http\Controllers\Core\Company\CompanySummaryModel;
use App\Http\Controllers\Core\Company\FiscalYearModel;
use App\Http\Controllers\Core\Company\SubFiscalYearModel;
use App\Http\Controllers\Core\Dividend\DividendColumnModel;
use App\Http\Controllers\Core\Dividend\DividendDetailModel;
use App\Http\Controllers\Core\Dividend\DividendModel;
use App\Http\Controllers\Core\FiscalYear\CompanyFiscalYearModel;
use App\Http\Controllers\Core\Investment\InvestmentModel;
use App\Http\Controllers\Core\Investment\InvestmentTabModel;
use App\Http\Controllers\Core\ProposedDividend\ProposedDividendModel;
use App\Http\Controllers\Core\User\UserModel;
use App\Http\Controllers\Core\Experts\CompanyExpertModel;
use App\Http\Controllers\Core\Experts\ExpertsModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;


class ApiCompanyController extends Controller
{
    public function getCompanyFromSlug($slug) {
        $data = CompanyModel::with('getSector')->where('slug', $slug)->firstOrFail();        
        $data->asset = $data->asset ? route('get-image-asset-type-filename', ['company-logo', $data->asset]) : NULL;
        $data->profile = '<p>' . implode('</p><p>', array_filter(explode("\r\n", $data->profile))) . '</p>';
        $data->contact = '<p>' . implode('</p><p>', array_filter(explode("\r\n", $data->contact))) . '</p>';
        $data->sector  = $data->getSector ? $data->getSector->name : NULL;
        $data->recent_earning = $data->recent_earning ? $data->recent_earning : "N/A";
        $data->employees = $data->employees ? $data->employees : "N/A";
        $data->branch_number = $data->branch_number ? $data->branch_number : "N/A";
        
        $data->cash_dividend = Null !== $data->dividendDetail() ? $data->dividendDetail()->cash_dividend : "N/A";
        $data->bonus_dividend = Null !== $data->dividendDetail() ? $data->dividendDetail()->bonus_share : "N/A";
        $data->payout_ratio = $data->payoutRatio();
        if(isset($data->dividend)){
            unset($data->dividend);
        }

        return response()->json([
            'data' => $data,
            'option'    =>  (new CompanyModel)->rating
        ]);
    }

    public function getCompanyValuation($no_of_company) {
        $companies = CompanyModel::orderBy('percent', 'DESC')
                                ->orderBy('company_name', 'ASC')
                                ->paginate($no_of_company);
        $data = [];
        foreach($companies as $index => $company) {
            if($company->fair_value_rating){
                $data[] = [
                    'id'          => $company->id,
                    'company_name'=> $company->company_name,
                    'percent'  =>  $company->percent,
                    //'summary'     => ((new CompanySummaryModel)->getSummary($company->id))['summary'],
                    'slug'        => $company->slug,                         
                    'short_code'  => $company->short_code,
                    'fair_value_rating'    =>  $company->fair_value_rating
               ];
            }
        }
        return $data;
    }

    public function postCompanyValuationCreate() {
        $data = request()->all();
    }

    public function getQuotes($slug) {

        $tabs = CompanyQuoteTabModel::get();
        $company_id = CompanyModel::where('slug', $slug)->firstOrFail()->id;
        $quote_values = CompanyQuoteValuesModel::where('company_id', $company_id)->get();
        $quote_headings = CompanyQuoteModel::orderBy('ordering', 'ASC')->get();
        
        $_return_data = [];        
        foreach($tabs as $t) {
            //$t->headings = [];
            
            $_return_data[$t->id]['tab_name'] = $t->tab_name;
            $_return_data[$t->id]['tab_id'] = $t->id;
            $_return_data[$t->id]['headings'] = [];
            
            //$return_data[$t->_id]->data = []; 
        }
        
        foreach($quote_headings as $q) {
            if(isset($_return_data[$q->tab_id]))
                $_return_data[$q->tab_id]['headings'][$q->id]['heading_name'] = $q->display_name;
                $_return_data[$q->tab_id]['headings'][$q->id]['value'] = '-';
        }

        foreach($quote_values as $q) {
            foreach($_return_data as $tab_id => $data) {
                foreach($quote_values as $quote_value) {
                    if(isset($_return_data[$tab_id]['headings'][$quote_value->quote_id]['value'])) {
                        $_return_data[$tab_id]['headings'][$quote_value->quote_id]['value'] = $quote_value->value;
                    }
                }
            }
        }

        $return_data = array_values($_return_data);

        foreach($return_data as $index => $r) {
            $return_data[$index]['headings'] = array_values($r['headings']);
        }


        return response()->json($return_data);
    }

    public function getQuotesGraph($slug) {
        $company_id = CompanyModel::where('slug', $slug)->firstOrFail()->id;
        $fiscal_years = FiscalYearModel::orderBy('id')->get();

        $return_value = [];
        foreach ($fiscal_years as $index=>$fiscal_year) {
            $return_value[$index]['year'] = $fiscal_year->fiscal_year;
            $return_value[$index]['value'] = rand(100,500);
        }

        return $return_value;
    }

    public function getFinancials($slug) {
        $company_id = CompanyModel::where('slug', $slug)->firstOrFail()->id;
        $tabs = CompanyFinancialsTabsModel::get();
        $headings = CompanyFinancialsHeadingsModel::get();
        $as_of_data = CompanyFinancialsAsOfModel::where('company_id', $company_id)->get();
        $data = CompanyFinancialsModel::where('company_id', $company_id)->get();

        $_return_data = [];
        foreach($tabs as $t) {
            $_return_data[$t->id]['tab_name'] = $t->tab_name;
            $_return_data[$t->id]['as_of'] = '-';
            $_return_data[$t->id]['headings'] = [];
        }

        foreach($as_of_data as $d) {
            if(isset($_return_data[$d->tab_id])) {
                $_return_data[$d->tab_id]['as_of'] = $d->as_of;
            }
        }

        foreach($headings as $h) {
            if(isset($_return_data[$h->tab_id])) {
                $_return_data[$h->tab_id]['headings'][$h->id]['heading_name'] = $h->heading;
                $_return_data[$h->tab_id]['headings'][$h->id]['value'] = '-';
                foreach($data as $d) {
                    if(isset($_return_data[$h->tab_id]['headings'][$d->heading_id])) {
                        $_return_data[$h->tab_id]['headings'][$d->heading_id]['value'] = $d->value;
                    }
                }
            }
        }

        $return_data = array_values($_return_data);
        foreach($return_data as $index => $r) {
            $return_data[$index]['headings'] = array_values($r['headings']);
        }

        $decimals = ['Price/Book', 'Price/Sales', 'Price/Cash Flow', 'Price/Earnings', 'Quick Ratio', 'Current Ratio', 'Interest Coverage', 'Debt/Equity'];
        $percentage = ['Revenue %', 'Operating Income %', 'Net Income %', 'Diluted EPS %', 'Return on Equity %', 'Return on Investment Capital %', 'Net Margin %', 'Profitability'];

        foreach($return_data as $tab_index => $tabs) {
            foreach($tabs['headings'] as $heading_index => $headings) {
                if(in_array($headings['heading_name'], $decimals)) {
                    $headings['value'] = is_numeric($headings['value']) ? $headings['value'] : '-';
                    
                        $return_data[$tab_index]['headings'][$heading_index]['value'] = $headings['value'] == '-' ? $headings['value'] : ((int) ($headings['value'] * 100) ) / 100;
                    
                } else if(in_array($headings['heading_name'], $percentage)) {
                    $headings['value'] = is_numeric($headings['value']) ? $headings['value'] : '-';
                    $return_data[$tab_index]['headings'][$heading_index]['value'] = $headings['value'] == '-' ? $headings['value'] : ((int) ($headings['value'] * 100 * 100) ) / 100;
                }
            }
        }

        return response()->json($return_data);

    }

    public function getFinancialsChildTabs($slug, $tab_id){
        $company = CompanyModel::where('slug', $slug)->firstOrFail();
        $sector = $company->getSector;

        if($sector->has_child_tab=='yes'){
            $tab = CompanyBalanceSheetTabsModel::where('id',$tab_id)->first();
            $child_tabs = $tab->childTabs;
            return response()->json(['data'=>$child_tabs]);
        }else{
            return response()->json(['data'=>[]]);
        }
    }

    public function getFinancialsTableData($slug) {
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $input = request()->all();
        $input['type'] = isset($input['type']) ? $input['type'] : 'yearly';
        $input['no_of_years'] = isset($input['no_of_years']) ? $input['no_of_years'] : 3;

        $return_data = (new BalanceSheetDataHelper)->getData($company, $input['type'], true, $input['no_of_years'], false);
        
        return response()->json($return_data);
    }

    public function getFinancialsTableDataFull($slug, $tab_id) {
        //($company, $type, $show_only_summary=true, $no_of_years=0, $tab_id=0)
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $user = auth('api')->user();

        $input = request()->all();
        $input['type'] = isset($input['type']) ? $input['type'] : 'yearly';
        $input['no_of_years'] = isset($user) ? isset($input['no_of_years']) ? $input['no_of_years'] : 0 : 2;
        $blurred = isset($user) ? false : true;
        $return_data = (new BalanceSheetDataHelper)->getData($company, $input['type'], false, $input['no_of_years'], $blurred, $tab_id);
        return response()->json($return_data);
    }

    public function getFinancialsTabs($slug) {
        $sector_id = CompanyModel::where('slug',$slug)->first()->sector_id;
        $tabs = CompanyBalanceSheetTabsModel::where('is_parent', 'yes')
                                            ->orderBy('ordering', 'ASC')
                                            ->get();
        return response()->json(['sector_id'=>$sector_id, 'tabs'=>$tabs]);
    }

    public function getHistoricalFinancialsTabs($slug) {
        $tabs_table = (new CompanyBalanceSheetTabsModel)->getTable();
        $sector_id = CompanyModel::where('slug',$slug)->first()->sector_id;

        $tabs = \App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel::join($tabs_table, $tabs_table.'.id', '=', 'tab_id')
                                                ->where('sector_id', $sector_id)
                                                ->select($tabs_table.'.*')
                                                ->get();

        return response()->json(['sector_id'=>$sector_id, 'tabs'=>$tabs]);

    }

    public function getCompanyList($slug=NULL) {
        $sectors = CompanySectorModel::orderBy('name', 'ASC')->get();
        $_company = CompanyModel::select([
            'company_name', 'id', 'slug', 'short_code', 'sector_id'
        ])->orderBy('company_name', 'ASC');

        $slg = [
            'bonds' => 1,
            'funds' => 2,
            'shares' => 4,
            'stocks' => 3
        ];

        if(in_array($slug, array_keys($slg))) {
            $_company = $_company->where('type_id', $slg[$slug]);
        }

        $_company = $_company->get();

        $company = [];
        
        foreach($_company as $c) {
            $company[$c->sector_id][] = $c;
        }

        $data = [];
        foreach($sectors as $s) {
            $temp = [];
            $temp['sector_name'] = $s->name;
            $temp['data'] = [];
            if(isset($company[$s->id])) {
                foreach($company[$s->id] as $c) {
                    $temp['data'][] = ['id' => $c->id, 'slug' => $c->slug, 'short_code' => $c->short_code, 'company_name' => $c->company_name];
                }
            }

            $data[] = $temp;
        }
        
        return response()->json(['data' => $data]);
    }


    public function getProposedDividendTable(){

        $return_data = [];
        $return_data['title'] = 'Dividends';
        $return_data['to'] = 'proposed-dividend-table-full';
        $return_data['header'] = [
            [
                "key" => 'company',
                "alias" => "Company"
            ],
            [
                "key" => 'bonus',
                "alias" => "Bonus"
            ],
            [
                "key" => 'cash',
                "alias" => "Cash"
            ],
            [
                "key" => 'total_dividend',
                "alias" => "Total"
            ],
            [
                "key" => 'book_closure',
                "alias" => "Book Close"
            ],
            [
                "key" => 'year',
                "alias" => "Year"
            ],
        ];

        $return['body'] = [];

        $proposed_dividends = ProposedDividendModel::orderBy("book_closure_date", "DESC")->with("fiscalYear")->take(5)->get();

        foreach($proposed_dividends as $p) {

            // $temp['symbol'] = [
            //     "key" => "symbol",
            //     "value" => $p->symbol,
            //     "link"  =>  [
            //         "type" => "text",
            //         "to" => NULL
            //     ]
            // ];
            $temp['company'] = [
                "key" => "company",
                "value" => $p->company_name,
                "link"  =>  NULL
            ];

            $temp['bonus'] = [
                "key" => "bonus",
                "value" => round((float) $p->bonus*100, 2).'%',
                "link"  =>  NULL
            ];

            $temp['cash'] = [
                "key" => "cash",
                "value" => round((float) $p->cash*100, 2).'%',
                "link"  =>  NULL
            ];

            $temp['total_dividend'] = [
                "key" => "total_dividend",
                "value" => round((float) ($p->cash + $p->bonus)*100, 2).'%',
                "link"  =>  NULL
            ];
            $temp['book_closure'] = [
                "key" => "book_closure",
                "value" => Carbon::create($p->book_closure_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];

            $temp['year'] = [
                "key" => "year",
                "value" => $p->fiscalYear->fiscal_year,
                "link"  =>  NULL
            ];

            $return_data['body'][] = $temp;
        }

        return response()->json($return_data);
    }

    public function getProposedDividendTableFull(){

        $input = request()->all();

        $return_data = [];

        $return_data['title'] = 'Proposed Dividend Table';
        $return_data['header'] = [
            [
                "key"=>"symbol",
                "alias"=>"Symbol"
            ],
            [
                "key"=>"company_name",
                "alias"=>"Company Name"
            ],
            [
                "key"=>"bonus",
                "alias"=>"Bonus Dividend(%)"
            ],
            [
                "key"=>"cash",
                "alias"=>"Cash Dividend(%)"
            ],
            [
                "key"=>"total_dividend",
                "alias"=>"Total Dividend(%)"
            ],
            [
                "key"=>"year",
                "alias"=>"Year"
            ],
            [
                "key"=>"sector",
                "alias"=>"Sector"
            ],
            [
                "key"=>"distribution_date",
                "alias"=>"Distribution Date"
            ],
            [
                "key"=>"book_closure_date",
                "alias"=>"Book Closure Date"
            ]
        ];

        $return_data['body'] = [];

        $proposed_dividends = ProposedDividendModel::orderBy("book_closure_date", "DESC");

        if(isset($input['fiscal_year_id']) && $input['fiscal_year_id'] != 'null')  {
            $proposed_dividends = $proposed_dividends->where('fiscal_year_id', $input['fiscal_year_id']);
        }
        if(isset($input['sector_id']) && $input['sector_id'] != 'null') {
            $proposed_dividends = $proposed_dividends->where('sector_id', $input['sector_id']);
        }

        $proposed_dividends = $proposed_dividends->with("fiscalYear", "sector")->get();

        foreach($proposed_dividends as $p) {
            $temp['symbol'] = [
                "key" => "symbol",
                "value" => $p->symbol,
                "link"  =>  [
                    "type" => "text",
                    "to" => NULL
                ]
            ];
            $temp['company_name'] = [
                "key" => "company_name",
                "value" => $p->company_name,
                "link"  =>  [
                    "type" => "text",
                    "to" => NULL
                ]
            ];
            $temp['cash'] = [
                "key" => "cash",
                "value" => round((float) $p->cash*100, 2).'%',
                "link"  =>  NULL
            ];
            $temp['bonus'] = [
                "key" => "bonus",
                "value" => round((float) $p->bonus*100, 2).'%',
                "link"  =>  NULL
            ];
            $temp['total_dividend'] = [
                "key" => "total_dividend",
                "value" => round((float) ($p->cash + $p->bonus)*100, 2).'%',
                "link"  =>  NULL
            ];
            $temp['year'] = [
                "key" => "year",
                "value" => $p->fiscalYear->fiscal_year,
                "link"  =>  NULL
            ];
            $temp['sector'] = [
                "key" => "sector",
                "value" => $p->sector->name,
                "link"  =>  NULL
            ];
            $temp['distribution_date'] = [
                "key" => "distribution_date",
                "value" => Carbon::create($p->distribution_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];
            $temp['book_closure_date'] = [
                "key" => "book_closure_date",
                "value" => Carbon::create($p->book_closure_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];

            $return_data['body'][] = $temp;
        }
        return $return_data;
    }

    public function getAgmSgmTable() {

        $return_data = [];
        $return_data['title'] = 'AGM SGM';
        $return_data['to'] = 'agm-sgm-table-full';
        $return_data['header'] = [
            [
                'key' => 'company',
                'alias' => 'Company'
            ],
            [
                'key' => 'book_closure',
                'alias' => 'Book Close'
            ],
            [
                'key' => 'agm_date',
                'alias' => 'AGM Date'
            ],
            [
                'key' => 'year',
                'alias' => 'Year'
            ],
            [
                'key' => 'venue',
                'alias' => 'Venue'
            ],
        ];

        $return_data['body'] = [];
        $agmSgmData = AgmSgmModel::orderBy('agm_date', 'DESC')->take(5)->get();

        foreach($agmSgmData as $agmSgm){
            // $temp['symbol'] = [
            //     "key" => "symbol",
            //     "value" => $agmSgm->symbol,
            //     "link"  =>  [
            //         "type" => "text",
            //         "to" => NULL
            //     ]
            // ];
            // $temp['agm'] = [
            //     "key" => "agm",
            //     "value" => $agmSgm->agm,
            //     "link"  =>  NULL
            // ];
            $temp['company'] = [
                "key" => "company",
                "value" => $agmSgm->company_name,
                "link"  =>  NULL
            ];
            $temp['book_closure'] = [
                "key" => "book_closure",
                "value" => Carbon::create($agmSgm->book_closure_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];
            $temp['agm_date'] = [
                "key" => "agm_date",
                "value" => Carbon::create($agmSgm->agm_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];
            $temp['year'] = [
                "key" => "year",
                "value" => $agmSgm->fiscalYear->fiscal_year,
                "link"  =>  NULL
            ];

            $temp['venue'] = [
                "key" => "venue",
                "value" => $agmSgm->venue,
                "link"  =>  NULL
            ];

            $return_data['body'][] = $temp;
        }
        return $return_data;
    }

    public function getAgmSgmTableFull(){

        $input = request()->all();

        $return_data = [];

        $return_data['title'] = 'AM SGM Table';

        $return_data['header'] = [
            [
                "key"=>"symbol",
                "alias"=>"Symbol"
            ],
            [
                "key"=>"company_name",
                "alias"=>"Company Name"
            ],
            [
                "key"=>"agm",
                "alias"=>"AGM"
            ],
            [
                "key"=>"venue_time",
                "alias"=>"Venue/Time"
            ],
            [
                "key"=>"year",
                "alias"=>"Year"
            ],
            [
                "key"=>"sector",
                "alias"=>"Sector"
            ],
            [
                "key"=>"agm_date",
                "alias"=>"AGM Date"
            ],
            [
                "key"=>"book_closure_date",
                "alias"=>"Book Closure Date"
            ],
            [
                "key"=>"agenda",
                "alias"=>"Agenda"
            ]
        ];

        $return_data['body'] = [];

        $agm_sgm_data = AgmSgmModel::orderBy("agm_date", "DESC");

        if(isset($input['fiscal_year_id']) && $input['fiscal_year_id'] != 'null')  {
            $agm_sgm_data = $agm_sgm_data->where('fiscal_year_id', $input['fiscal_year_id']);
        }
        if(isset($input['sector_id']) && $input['sector_id'] != 'null') {
            $agm_sgm_data = $agm_sgm_data->where('sector_id', $input['sector_id']);
        }

        $agm_sgm_data = $agm_sgm_data->with("fiscalYear", "sector")->get();

        foreach($agm_sgm_data as $p) {
            $temp['symbol'] = [
                "key" => "symbol",
                "value" => $p->symbol,
                "link"  =>  [
                    "type" => "text",
                    "to" => NULL
                ]
            ];
            $temp['company_name'] = [
                "key" => "company_name",
                "value" => $p->company_name,
                "link"  =>  [
                    "type" => "text",
                    "to" => NULL
                ]
            ];
            $temp['agm'] = [
                "key" => "agm",
                "value" => $p->agm,
                "link"  =>  NULL
            ];
            $temp['venue_time'] = [
                "key" => "venue_time",
                "value" => $p->venue.' ('.$p->time.' )',
                "link"  =>  NULL
            ];
            $temp['year'] = [
                "key" => "year",
                "value" => $p->fiscalYear->fiscal_year,
                "link"  =>  NULL
            ];
            $temp['sector'] = [
                "key" => "sector",
                "value" => $p->sector->name,
                "link"  =>  NULL
            ];
            $temp['agm_date'] = [
                "key" => "agm_date",
                "value" => Carbon::create($p->agm_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];
            $temp['book_closure_date'] = [
                "key" => "book_closure_date",
                "value" => Carbon::create($p->book_closure_date)->format('d/m/Y'),
                "link"  =>  NULL
            ];
            $temp['agenda'] = [
                "key" => "agenda",
                "value" => $p->agenda,
                "link" => NULL
            ];

            $return_data['body'][] = $temp;
        }
        return $return_data;
    }

    public function getValuationTableDataFull($slug) {
        $user = auth('api')->user();

        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $input = request()->all();
        $input['type'] = isset($input['type']) ? $input['type'] : 'yearly';
        $input['no_of_years'] = isset($user)? isset($input['no_of_years']) ? $input['no_of_years'] : 0: 2;
        $input['blurred_part'] = isset($user) ? false : true;

        $return_data = (new BalanceSheetDataHelper)->getValuationData($company, $input['type'], false, $input['no_of_years'], $input['blurred_part']);

        return response()->json(['data' => $return_data]);
    }

    public function getDividendType($slug){
        $company = CompanyModel::where('slug', $slug)->firstOrFail();
        $type = $company->dividendType ? $company->dividendType->type : Null;
        return response()->json(['type' => $type]);
    }

    public function getDividendTableDataFull($slug){
        $company = CompanyModel::where('slug', $slug)->firstOrFail();
        $type = $company->dividendType ? $company->dividendType->type : Null;

        $input = request()->all();
        $input['no_of_years'] = isset($input['no_of_years']) ? $input['no_of_years'] : 0;
        $columns = DividendColumnModel::where('type_id', $company->dividend_type_id)->get();

        $company_years_table = (new CompanyFiscalYearModel)->getTable(); 
        $years_table = (new FiscalYearModel)->getTable();
        $_years = FiscalYearModel::join($company_years_table, $company_years_table.'.fiscal_year_id', '=',  $years_table.'.id')->where('company_id', $company->id)->orderBy('ordering', 'DESC')->take($input['no_of_years'])->select($years_table.'.*')->get(); 

        $years = [];
        foreach ($_years as $year){
            $years[$year->id] = $year->fiscal_year;
        }
        
        if ($type == 'Dividend')
            $decimals = ['Dividend Per Share','Dividend Payout Ratio','Trailing Dividend Yield'];
        else if ($type == 'Right Share')
            $decimals = ['Right Share Ratio','Number of New Shares'];

        $dividend_data = [];
        foreach ($columns as $col){
            $dividend_data[$col->id] = [];
            foreach ($_years as $y){
                $row = DividendModel::where('company_id', $company->id)->where('column_id', $col->id)->where('fiscal_year_id',$y->id)->first();

                if ($row){
                    if(in_array($col->column_name, $decimals)){
                        $value = $row->value == '-'? $row->value : ((int) ($row->value * 100))/ 100;
                        $row->value = $value;
                    }
                    $dividend_data[$col->id][$y->id] = $row->value;
                }
                else{
                    $dividend_data[$col->id][$y->id] = '-';
                }
            }
        }

        $return_data = [];
        if ($type == 'Dividend')
            $return_data['title'] = 'Dividend';
        else if ($type == 'Right Share')
            $return_data['title'] = 'Right Share';
        else
            $return_data['title'] = '';

        $return_data['table']['header'] = [
            [
                "key"=>"e",
                "alias"=>""
            ],
        ];

        foreach ($_years as $year){
            $return_data['table']['header'][] = [
                'key' => $year->fiscal_year,
                'alias' => $year->fiscal_year
            ];
        }

        $return_data['table']['body'] = [];

        foreach($columns as $c) {
            $temp['e'] = [
                "key" => "e",
                "value" => $c->column_name,
            ];

            foreach ($dividend_data as $column_id => $dd){
                if ($column_id == $c->id){
                    foreach ($dd as $fiscal_year_id=>$value){
                        if(isset($years[$fiscal_year_id])){

                            $temp[$years[$fiscal_year_id]] = [
                                "key" => $years[$fiscal_year_id],
                                "value" => $value
                            ];
                        }
                    }
                }
            }
            $return_data['table']['body'][] = $temp;
        }

        if ($type == 'Dividend'){
            $_dividend_details = DividendDetailModel::where('company_id', $company->id)
                                // ->select('book_closure_date','dividend_type','bonus_share', 'cash_dividend')
                                ->orderBy('book_closure_date','DESC')->get();
        
            
            $return_data['details']['header'][] = [
                "key" => 'fiscal_year',
                "alias" => 'Fiscal Year'
            ];
            $return_data['details']['header'][] = [
                "key" => 'bonus_share',
                "alias" => 'Bonus Share (%)'
            ];
            $return_data['details']['header'][] = [
                "key" => 'cash_dividend',
                "alias" => 'Cash Dividend (%)'
            ];
            $return_data['details']['header'][] = [
                "key" => 'total_dividend',
                "alias" => 'Total Dividend (%)'
            ];
            $return_data['details']['header'][] = [
                "key" => 'book_closure_date',
                "alias" => 'Bookclose Date'
            ];

            foreach ($_dividend_details as $index=>$dd){
                $temp = [];
                foreach ($dd->getOriginal() as $attribute=>$value){
                    $temp[$attribute] = [
                        'key' => $attribute,
                        'value' => $value
                    ];
                    if($attribute == 'book_closure_date'){
                        $temp[$attribute]['value'] = Carbon::create($value)->format('d-m-Y');
                    }
                    if(in_array($attribute, ['total_dividend', 'cash_dividend', 'bonus_share'])){
                        $temp[$attribute]['value'] = number_format($value, 2);
                    }
                }
                $return_data['details']['body'][] = $temp;
            }
        }
        return response()->json(['data' => $return_data]);
    }

    public function getPerformanceTableDataFull($slug) {
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $input = request()->all();
        $input['type'] = isset($input['type']) ? $input['type'] : 'yearly';
        $input['no_of_years'] = isset($input['no_of_years']) ? $input['no_of_years'] : 0;

        $return_data = (new BalanceSheetDataHelper)->getPerformanceData($company, $input['type'], false, $input['no_of_years']);

        return response()->json(['data' => $return_data]);
    }

    public function getFairValue($slug){
        $company = CompanyModel::where('slug', $slug)->firstOrFail();
        
        $data['fair_value_rating'] = [
            'rating' => $company->fair_value_rating,
            'updated_at' => $company->rating_updated_at
        ];
        $data['expert'] = [];

        return response()->json(['data' => $data]);
    }

    public function getCompanyExpert($slug){
        $no_of_items = request()->get('no_of_items', 6);
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $_experts_ids = CompanyExpertModel::select('expert_id')->where('company_id', $company->id)->get();
        $experts_ids = [];
        foreach ($_experts_ids as $e){
            $experts_ids[] = $e->expert_id;
        }

        $experts = ExpertsModel::where('is_active', 'yes')
                        ->whereIn('id', $experts_ids)
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate($no_of_items);

        foreach($experts as $index => $e) {
            if($e->asset_type == 'image') {
                $e->asset = route('get-image-asset-type-filename', ['experts', $e->asset]);
            }
        }
        return response()->json(['data' => $experts]);

    }

    public function login() {
        $data = request()->all();
        $rule = [
            'name' => ['string', 'required'],
            'email' => ['string', 'required'],
            'user_id' => ['nullable'],
            'password' => ['required_without:user_id']
        ];

        $validator = \Validator::make($data, $rule);
        if (!$validator->fails()){
            $record = (new UserModel)->firstOrCreate([
                'fb_id' => $data['user_id'],
                'email' => $data['email']
            ]);
            $record->name = $data['name'];
            $record->access_token = $data['access_token'];
            $record->picture = $data['picture'];
            $record->save();
            $record->premium_token = '12345678';
        }else{
            $error_messages = [];
            $errors = $validator->errors();
            foreach($rule as $index=>$value){
                if($errors->has($index)) {
                    foreach($errors->get($index) as $e) {
                        $error_messages[$index][] = $e;
                    }
                }
            }
            return response()->json(['message'=>$error_messages],500);
        }
        return response()->json(['message'=>'Logged in successfully','data'=>$record], 200);
    }

    public function checkCompanyHasHistoricalTabs($slug) {
        $sector_id = CompanyModel::where('slug', $slug)->first()->sector_id;
        return $this->checkSectorHasHistoricalTabs($sector_id);
    }

    public function checkSectorHasHistoricalTabs($sector_id) {
        $check = \App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel::where('sector_id', $sector_id)->get();

        return ['data' => $check];
    }
}