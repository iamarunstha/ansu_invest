<?php

namespace App\Http\Controllers\Core\Company;

use App\Http\Controllers\Core\Dividend\DividendTypeModel;
use App\Http\Controllers\Core\Experts\ExpertsModel;
use App\Http\Controllers\Core\Ownership\OwnershipModel;
use App\Http\Controllers\Core\TrailingReturns\CompanyTrailingReturnsModel;
use App\Http\Controllers\Core\Stock\StockModel;
use App\Http\Controllers\Core\Dividend\DividendDetailModel;
use App\Http\Controllers\Core\Dividend\DividendModel;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    public $timestamps = false;
    protected $table = 'company';
    protected $guarded = ['id'];
    public $rating = [
        'Highly undervalued',
        'Undervalued',
        'Fairly Valued',
        'Overvalued',
        'Highly Overvalued'
    ];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function getType() {
        return $this->hasOne(CompanyTypeModel::class, 'id', 'type_id');
    }

    public function getCompanyData($id) {
        $view['data'] = $this->where('id', $id)->with('getType')->firstOrFail();

        return $view;
    }

    public function getSector() {
        return $this->hasOne(CompanySectorModel::class, 'id', 'sector_id');
    }

    public function dividendType(){
        return $this->hasOne(DividendTypeModel::class, 'id', 'dividend_type_id');
    }

    public function expert(){
        return $this->hasOne(ExpertsModel::class, 'id', 'expert_id');
    }

    public function trailingReturns() {
        return $this->hasMany(CompanyTrailingReturnsModel::class, 'company_id', 'id');
    }

    public function ownerships(){
        return $this->hasMany(OwnershipModel::class, 'company_id', 'id');
    }

    public function dividendDetail(){
        return $this->hasMany(DividendDetailModel::class, 'company_id', 'id')->orderBy('book_closure_date', 'desc')->first();
    }

    public function dividend(){
        return $this->hasMany(DividendModel::class, 'company_id', 'id')->where('column_id', 2)->with('fiscalYear');
    }

    public function payoutRatio(){
        if(Null !== $this->dividend){
            $ordering = 0;
            $value = Null;
            foreach($this->dividend as $d){
                if($d->fiscalYear->ordering > $ordering){
                    $value = $d->value;
                }
            }
            return is_numeric($value) ? $value : 'N/A';
        }else{
            return 'N/A';
        }
    }

    public function headings($tab_id = null){
        $headings = $this->belongsToMany(CompanyBalanceSheetHeadingsModel::class, CompanyHeadingsModel::class, 'company_id', 'heading_id');
        if($tab_id){
            $headings = $headings->where('tab_id', $tab_id);
        }
        return $headings;
    }
}
