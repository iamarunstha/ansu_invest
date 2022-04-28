<?php

namespace App\Http\Controllers\Core\AgmSgm;

use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Database\Eloquent\Model;

class AgmSgmModel extends Model
{
    protected $table = 'agm_sgm';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
        return [
            'symbol'=>['string','required'],
            'company_name' => ['string','required'],
            'agm' => ['integer', 'required', 'min:1'],
            'venue' => ['string', 'required'],
            'time' => ['required'],
            'agm_date' => ['date', 'required'],
            'fiscal_year_id' => ['required', 'exists:'.(new FiscalYearModel)->getTable().',id'],
            'sector_id' => ['required', 'exists:'.(new SectorModel)->getTable().',id'],
            'book_closure_date' => ['required', 'date'],
            'agenda' => ['required', 'string']
        ];
    }

    public function fiscalYear() {
    	return $this->belongsTo(FiscalYearModel::class, 'fiscal_year_id', 'id');
    }
    public function sector() {
    	return $this->belongsTo(SectorModel::class, 'sector_id', 'id');
    }
}