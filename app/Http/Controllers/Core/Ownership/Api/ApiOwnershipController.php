<?php 

namespace App\Http\Controllers\Core\Ownership\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Ownership\OwnershipColumnModel;
use App\Http\Controllers\Core\Ownership\OwnershipModel;
use App\Http\Controllers\Core\Ownership\OwnershipNameModel;
use App\Http\Controllers\Core\Ownership\OwnershipTabModel;

class ApiOwnershipController extends Controller
{
	public function getOwnershipTabList(){
		$tabs = OwnershipTabModel::orderBy('ordering')->get();
		return response()->json($tabs);
	}
	public function getOwnershipList($slug, $tab_id)
	{
		$company = CompanyModel::where('slug', $slug)->first();
		$ownerships = [];
		$_ownerships = OwnershipModel::where('company_id', $company->id)->where('tab_id', $tab_id)->get();
		foreach($_ownerships as $o) {
			$ownerships[$o->name_id][] = $o;
		}
		
		
		$ownership_names = [];
		$_ownership_names = OwnershipNameModel::get();
		foreach($_ownership_names as $o) {
			$ownership_names[$o->id] = $o->name;
		}

		$ownership_columns = OwnershipColumnModel::orderBy('ordering','ASC')->get();

		$table = ['headers' => [], 'body'=>[]];
		$table['headers'] = [
			[
				'key' => 'name',
				'alias' => 'Name'
			],
		];
		foreach ($ownership_columns as $c){
			$table['headers'][] = [
				'key' => $c->column_name,
				'alias' => $c->display_name
			];
		}

		$temp = [];
		foreach ($ownerships as $name_id => $ownership){

			$temp['name'] = [
				'key' => 'name',
				'value' => $ownership_names[$name_id]
			];

			foreach($ownership_columns as $c) {
				$temp[$c->column_name] = [
					'key'	=>	$c->column_name,
					'value'	=>	'-'
				];
				foreach($ownership as $o) {
					if($o->column_id == $c->id) {
						$temp[$c->column_name] = [
							'key'	=>	$c->column_name,
							'value'	=>	$o->value
						];
						break;
					}
				}
			}
						
			$table['body'][] = $temp;
			$temp = [];
		}
		return response()->json(['table'=>$table]);
	}

}