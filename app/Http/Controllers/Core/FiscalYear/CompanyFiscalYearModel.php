<?php

namespace App\Http\Controllers\Core\FiscalYear;

use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Database\Eloquent\Model;

class CompanyFiscalYearModel extends Model
{
    protected $table = 'company_fiscal_years';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'fiscal_year_id'	=>	['required', 'exists:'.(new FiscalYearModel)->getTable().',id'],

    		'company_id'	=>	['nullable','integer', 'exists:'.(new CompanyModel)->getTable().',id']
    	];

    	return $rule;
    }
}