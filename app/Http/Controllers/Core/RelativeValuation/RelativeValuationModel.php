<?php

namespace App\Http\Controllers\Core\RelativeValuation;

use Illuminate\Database\Eloquent\Model;

class RelativeValuationModel extends Model
{
    protected $table = 'company_relative_valuation';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule() {

    	$rule = [
    		// 'company_name'	=>	['required', 'exists:company,company_name'],
    		// 'description'	=>	['required']
    	];

    	return $rule;
    }
}
