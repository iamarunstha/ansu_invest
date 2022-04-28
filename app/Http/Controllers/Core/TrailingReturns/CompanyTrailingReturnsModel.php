<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class CompanyTrailingReturnsModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_trailing_returns';
    protected $guarded = ['id'];

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}