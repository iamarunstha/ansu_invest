<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanySectorModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_sector';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}
