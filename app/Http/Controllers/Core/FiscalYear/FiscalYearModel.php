<?php

namespace App\Http\Controllers\Core\FiscalYear;

use Illuminate\Database\Eloquent\Model;

class FiscalYearModel extends Model
{
    protected $table = 'fiscal_year';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'fiscal_year'	=>	['required'],

    		'ordering'	=>	['nullable','integer']
    	];

    	return $rule;
    }

    public function getColumns() {
        return \Schema::getColumnListing($this->table);
    }


}