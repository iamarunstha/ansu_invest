<?php

namespace App\Http\Controllers\Core\Performance;

use Illuminate\Database\Eloquent\Model;

class PerformanceDataModel extends Model
{
    protected $table = 'company_operating_performance_data';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule() {

    	$rule = [

    	];

    	return $rule;
    }
}