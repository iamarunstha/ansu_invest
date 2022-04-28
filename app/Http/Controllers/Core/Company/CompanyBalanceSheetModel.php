<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyBalanceSheetModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_balance_sheet';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getDownloadBalanceSheetRule($id = 0) {

    	$rule = [
    		'start_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id'],
            'end_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id'],
            'start_sub_year_id' => ['nullable','required_with:end_sub_year_id','exists:'.(new \App\Http\Controllers\Core\Company\SubFiscalYearModel)->getTable().',id'],
            'end_sub_year_id' => ['nullable','required_with:start_sub_year_id','exists:'.(new \App\Http\Controllers\Core\Company\SubFiscalYearModel)->getTable().',id'],
    	];

    	return $rule;
    }
}
