<?php

namespace App\Http\Controllers\Core\Dividend;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;

class DividendModel extends Model
{
    protected $table = 'dividend_values';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getDownloadDividendRule($id = 0) {

    	$rule = [
    		'start_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id'],
            'end_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id']
    	];

    	return $rule;
    }

    public function fiscalYear(){
        return $this->hasOne(FiscalYearModel::class, 'id', 'fiscal_year_id');
    }
}