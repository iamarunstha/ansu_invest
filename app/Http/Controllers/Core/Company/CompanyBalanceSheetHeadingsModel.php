<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyBalanceSheetHeadingsModel extends Model
{
    public $timestamps = false;
    protected $table = 'balance_sheet';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function tab(){
        return $this->hasOne(CompanyBalanceSheetTabsModel::class, 'id', 'tab_id');
    }
}
