<?php

namespace App\Http\Controllers\Core\TrailingReturns\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use App\Http\Controllers\Core\TrailingReturns\CompanyTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\NepseTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\SectorTrailingReturnsModel;
use App\Http\Controllers\Core\TrailingReturns\TrailingReturnsColumnModel;
use App\Http\Controllers\Core\TrailingReturns\TrailingReturnsTabModel;
use App\Http\Controllers\Core\TrailingReturns\TrailingReturnsTypeModel;
use Illuminate\Http\Request;
use Validator;


class ApiTrailingReturnsController extends Controller
{
    public function getTabList() {
    	$tabs = TrailingReturnsTabModel::select('id','tab_name')->orderBy('ordering','ASC')->get();
    	return response()->json($tabs);
    }

    public function getTrailingReturns($slug, $tab_id){
    	$company = CompanyModel::where('slug',$slug)->first();
    	$sector = SectorModel::where('id',$company->sector_id)->first();

    	$headers = TrailingReturnsColumnModel::where('tab_id',$tab_id)->get();

    	$types = TrailingReturnsTypeModel::orderBy('ordering','ASC')->get();
    	
    	$side_column = [$company->company_name, $sector->name, 'NEPSE'];
		
		$table = ['headers' => [], 'body'=>[]];

		$table['headers'][] = [
			'key' => 'e',
			'alias' => ''
		];

		foreach ($headers as $h){
			$table['headers'][] = [
				'key' => $h->column_name,
				'alias' => $h->column_name
			];
		}

		foreach ($types as $t){
			$ctrs = CompanyTrailingReturnsModel::where('company_id', $company->id)->where('type_id', $t->id)->get();
			$strs = SectorTrailingReturnsModel::where('sector_id', $sector->id)->where('type_id', $t->id)->get();
			$ntrs = NepseTrailingReturnsModel::where('type_id', $t->id)->get();

			$temp = [];
			foreach ($side_column as $index=>$s){
				$row = [];
				$row['e'] = [
					'key' => 'e',
					'value' => $s
				];
				if ($index == 0){
					foreach($headers as $h){
						$added = false;
						foreach($ctrs as $ctr){
							if ($h->id == $ctr->column_id){
								$added =true;
								$row[$h->column_name] = [
									'key' => $h->column_name,
									'value' => $ctr->value.' %'
								];
							}
						}
						if(!$added){
							$row[$h->column_name] = [
								'key' => $h->column_name,
								'value' => '-'
							];
						}
					}
				}
				if ($index == 1){
					foreach($headers as $h){
						$added = false;
						foreach($strs as $str){
							if($h->id == $str->column_id){
								$added = true;
								$row[$h->column_name] = [
									'key' => $h->column_name,
									'value' => $str->value.' %'
								];
							}
						}
						if(!$added){
							$row[$h->column_name] = [
								'key' => $h->column_name,
								'value' => '-'
							];
						}
					}
				}
				if ($index == 2){
					foreach($headers as $h){
						$added = false;
						foreach($ntrs as $ntr){
							if($h->id == $ntr->column_id){
								$added = true;
								$row[$h->column_name] = [
									'key' => $h->column_name,
									'value' => $ntr->value.' %'
								];
							}
						}
						if(!$added){
							$row[$h->column_name] = [
								'key' => $h->column_name,
								'value' => '-'
							];
						}
					}
				}
				$temp[] = $row;
			}

			$table['body'][] = [
				'body_category' => $t->type_name,
				'category_description' => [
					'title' => $t->type_name,
					'id' => $t->id
				],
				'rows' => $temp
			];
		}
		return response()->json(['table' => $table]);
    }
}