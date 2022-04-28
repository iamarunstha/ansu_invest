<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyFinancialsHeadingsModel extends Model
{
    public $timestamps = false;
    protected $table = 'financials_headings';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}
