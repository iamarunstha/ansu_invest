<?php

namespace App\Http\Controllers\Core\Valuation;

use Illuminate\Database\Eloquent\Model;

class ValuationDataModel extends Model
{
    protected $table = 'company_valuation_data';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule() {

    	$rule = [

    	];

    	return $rule;
    }
}