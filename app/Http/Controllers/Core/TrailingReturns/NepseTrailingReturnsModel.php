<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class NepseTrailingReturnsModel extends Model
{
    public $timestamps = false;
    protected $table = 'nepse_trailing_returns';
    protected $guarded = ['id'];

    public function getRule() {

    	$rule = [
    		
    	];

    	return $rule;
    }
}