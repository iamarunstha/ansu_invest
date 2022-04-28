<?php

namespace App\Http\Controllers\Core\ProposedDividend;

use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Database\Eloquent\Model;

class ProposedDividendModel extends Model
{
    protected $table = 'proposed_dividend';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';
    public $timestamps = false;

    public function getRule(){
    	return [
    		'symbol' => ['required', 'string'],
    		'bonus' => ['required','numeric', 'min:0', 'max:100'],
    		'cash' => ['required','numeric', 'min:0', 'max:100'],
            'company_name' => ['required', 'string'],
    		'fiscal_year_id' => ['required', 'exists:'.(new FiscalYearModel)->getTable().',id'],
    		'sector_id' => ['required', 'exists:'.(new SectorModel)->getTable().',id'],
    		'distribution_date' => ['nullable', 'date'],
    		'book_closure_date' => ['required', 'date']
    	];

    }

    public function fiscalYear() {
    	return $this->belongsTo(FiscalYearModel::class, 'fiscal_year_id', 'id');
    }
    public function sector() {
        return $this->belongsTo(SectorModel::class, 'sector_id', 'id');
    }

    public function getBonusAttribute($value)
    {
        return \App\HelperController::convertIntegerToDecimal($value);
    }

    public function setBonusAttribute($value)
    {
        return $this->attributes['bonus'] = \App\HelperController::convertDecimalToInteger($value);
    }

    public function getCashAttribute($value)
    {
        return \App\HelperController::convertIntegerToDecimal($value);
    }

    public function setCashAttribute($value)
    {
        return $this->attributes['cash'] = \App\HelperController::convertDecimalToInteger($value);
    }
}
