<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class SectorTrailingReturnsModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_sector_trailing_returns';
    protected $guarded = ['id'];

    public function getRule() {

    	$rule = [
    		
    	];

    	return $rule;
    }
}