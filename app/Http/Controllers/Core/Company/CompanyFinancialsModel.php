<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyFinancialsModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_financials';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}
