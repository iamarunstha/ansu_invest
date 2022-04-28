<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyBalanceSheetTabsModel extends Model
{
    public $timestamps = false;
    protected $table = 'balance_sheet_tabs';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function childTabs(){
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parentTab(){
        return $this->hasOne(self::class, 'id', 'parent_id');
    }
}
