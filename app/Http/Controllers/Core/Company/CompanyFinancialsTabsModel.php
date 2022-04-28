<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyFinancialsTabsModel extends Model
{
    public $timestamps = false;
    protected $table = 'financials_headings_tabs';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}
