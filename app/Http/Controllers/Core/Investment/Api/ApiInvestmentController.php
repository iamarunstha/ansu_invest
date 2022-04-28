<?php

namespace App\Http\Controllers\Core\Investment\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Investment\InvestmentModel;
use App\Http\Controllers\Core\Investment\InvestmentTabModel;
use Carbon\Carbon;

class ApiInvestmentController extends Controller
{
	public function getInvestmentTabsList(){
		$title = 'Investing/Existing Issues';
		$tabs = InvestmentTabModel::orderBy('ordering', 'ASC')->select(['tab_name', 'id', 'show_in_half_table'])->get();
		$return_tabs = [];
		foreach($tabs as $tab){
			if ($tab->show_in_half_table){
				$return_tabs[] = $tab;
			}
		}
        return [
        	'title' => $title,
        	'table_link' => 'investment-existing-issues-table',
        	'tabs'	=> $return_tabs
        ];
	}

	public function getInvestmentExistingIssuesTable($id){
        $return_data = [];
        $return_data['title'] = 'Investment/Existing Issues Table';

        $tab = InvestmentTabModel::where('id', $id)->first();
        if ($tab->tab_name == 'IPO Local')
            return null;

        $return_data['tab_name'] = $tab->tab_name;
        $return_data['to'] = 'investment-existing-issues-table-full/';        
        $return_data["header"] = [];
        $return_data["body"] = [];


        // if ($tab->tab_name != 'IPO' || $tab->tab_name != 'Bonds/Debentures'){
        //     $return_data["header"][] = [
        //         "key"  => "symbol",
        //         "alias"=> "Symbol"
        //     ];
        // }
        $return_data["header"][] = [
            "key"=>"company",
            "alias"=>"Company"
        ];
        // if ($tab->tab_name == 'Right Share'){
        //     $return_data["header"][] = [
        //         "key"  => "ratio",
        //         "alias"=> "Ratio"
        //     ];
        // }
        $return_data["header"][] = [
                        "key"=>"units",
                        "alias"=>"Units"
                    ];
        if ($tab->tab_name == 'FPO'){
            $return_data["header"][] = [
                "key"  => "price",
                "alias"=> "Price"
            ];
        }
        $return_data["header"][] = [
                        "key"=>"opening_date",
                        "alias"=>"Opening Date"
                    ];
        $return_data["header"][] = [
                        "key"=>"closing_date",
                        "alias"=>"Closing Date"
                    ];
        $return_data["header"][] = [
                        "key"=>"status",
                        "alias"=>"Status"
                    ];
        $return_data["header"][] = [
                        "key"=>"view",
                        "alias"=>"View"
                    ];

        $entries = InvestmentModel::orderBy('company_name')->with('tab')->get();
        $temp = [];
        foreach($entries as $e) {
            if($e->tab_id == $tab->id) {
                // if ($tab->tab_name != 'IPO' || $tab->tab_name != 'Bonds/Debentures'){
                //     $temp["symbol"] = [
                //         "key" => "symbol",
                //         "value" => $e->symbol,
                //         "link"=> [
                //             "link_type" => "text",
                //             "to" => "/"
                //         ]
                //     ];
                // }

                $temp["company"] = [
                    "key" => "company",
                    "value" => $e->company_name,
                    "link"=> [
                        "link_type" => "text",
                        "to" => "/"
                    ]
                ];
                // if ($tab->tab_name == 'Right Share'){
                //     $temp["ratio"] = [
                //         "key"  => "ratio",
                //         "value"=> $e->symbol,
                //         "link" => NULL
                //     ];
                // }
                $temp["units"] = [
                    "key" => "units",
                    "value" => $e->units,
                    "link"=> NULL,
                ];
                if ($tab->tab_name == 'FPO'){
                    $temp["price"] = [
                        "key"  => "price",
                        "value"=> $e->price,
                        "link" => NULL
                    ];
                }
                $temp["opening_date"] = [
                    "key" => "opening_date",
                    "value" => str_replace('-', '/', Carbon::create($e->opening_date)->format('d-m-Y')),
                    "link"=> NULL,
                ];
                $temp["closing_date"] = [
                    "key" => "closing_date",
                    "value" => str_replace('-', '/', Carbon::create($e->closing_date)->format('d-m-Y')),
                    "link" => NULL
                ];
                $temp["status"] = [
                    "key" => "status",
                    "value" => $e->status,
                    "link"  => NULL
                ];
                $temp["view"] = [
                    "key" => "view",
                    "value" => $e->view,
                    "link"  => [
                        "link_type" => "icon",
                        "to" => "/"
                    ]
                ];
                $return_data["body"][] = $temp;
            }
        }

        return $return_data;
    }

    public function getInvestmentExistingIssuesTableFull($id){
        $return_data = [];
        $return_data['title'] = 'Investment/Existing Issues Table';

        $tab = InvestmentTabModel::where('id', $id)->first();       

        $return_data['tab_name'] = $tab->tab_name;

        $return_data["header"] = [];
        $return_data["body"] = [];

        $return_data["header"][] = [
            "key"  => "symbol",
            "alias"=> "Symbol"
        ];
        $return_data["header"][] = [
            "key"=>"company_name",
            "alias"=>"Company"
        ];
        if ($tab->tab_name == 'Right Share'){
            $return_data["header"][] = [
                "key"  => "ratio",
                "alias"=> "Ratio"
            ];
        }
        $return_data["header"][] = [
            "key"=>"units",
            "alias"=>"Units"
        ];
        $return_data["header"][] = [
            "key"  => "price",
            "alias"=> "Price"
        ];
        $return_data["header"][] = [
            "key"=>"opening_date",
            "alias"=>"Opening Date"
        ];
        $return_data["header"][] = [
            "key"=>"closing_date",
            "alias"=>"Closing Date"
        ];
        if ($tab->tab_name == 'Right Share'){
            $return_data["header"][] = [
                "key"  => "book_closure_date",
                "alias"=> "Book Closure Date"
            ];
        }else{
            $return_data["header"][] = [
                "key"  => "last_closing_date",
                "alias"=> "Last Closing Date"
            ];        	
        }
        $return_data["header"][] = [
            "key"=>"issue_manager",
            "alias"=>"Issue Manager"
        ];
        $return_data["header"][] = [
            "key"=>"status",
            "alias"=>"Status"
        ];
        $return_data["header"][] = [
            "key"=>"view",
            "alias"=>"View"
        ];
        if ($tab->tab_name == 'Right Share'){
            $return_data["header"][] = [
                "key"  => "eligibility_check",
                "alias"=> "Eligibility Check"
            ];
        }

        $entries = InvestmentModel::orderBy('company_name')->with('tab')->get();
        $temp = [];
        foreach($entries as $e) {
            if($e->tab_id == $tab->id) {
                $temp["symbol"] = [
                    "key" => "symbol",
                    "value" => $e->symbol,
                    "link"=> [
                        "link_type" => "text",
                        "to" => "/"
                    ]
                ];

                $temp["company_name"] = [
                    "key" => "company_name",
                    "value" => $e->company_name,
                    "link"=> [
                        "link_type" => "text",
                        "to" => "/"
                    ]
                ];
                if ($tab->tab_name == 'Right Share'){
                    $temp["ratio"] = [
                        "key"  => "ratio",
                        "value"=> $e->ratio,
                        "link" => NULL
                    ];
                }
                $temp["units"] = [
                    "key" => "units",
                    "value" => $e->units,
                    "link"=> NULL,
                ];
                $temp["price"] = [
                    "key"  => "price",
                    "value"=> $e->price,
                    "link" => NULL
                ];
                $temp["opening_date"] = [
                    "key" => "opening_date",
                    "value" => $e->opening_date,
                    "link"=> NULL,
                ];
                $temp["closing_date"] = [
                    "key" => "closing_date",
                    "value" => $e->closing_date,
                    "link" => NULL
                ];
                if ($tab->tab_name == 'Right Share'){
                    $temp["book_closure_date"] = [
                        "key"  => "book_closure_date",
                        "value"=> $e->book_closure_date,
                        "link" => NULL
                    ];
                }else{
                    $temp["last_closing_date"] = [
                        "key"  => "last_closing_date",
                        "value"=> $e->last_closing_date,
                        "link" => NULL
                    ];                	
                }
                $temp["status"] = [
                    "key" => "status",
                    "value" => $e->status,
                    "link"  => NULL
                ];
                $temp["view"] = [
                    "key" => "view",
                    "value" => $e->view,
                    "link"  => [
                        "link_type" => "icon",
                        "to" => "/"
                    ]
                ];
                if($tab->tab_name == 'Right Share'){
                	$temp['eligibility_check'] = [
                		"key" => "eligibility_check",
                		"value" => $e->eligibility_check,
                		"link"  => NULL
                	];
                }
                $return_data["body"][] = $temp;
            }
        }
        return $return_data;
    }
}