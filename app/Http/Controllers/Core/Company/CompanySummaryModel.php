<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanySummaryModel extends Model
{
    protected $table = 'company_summary';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function getSummary($company_id) {
        return self::where('company_id', $company_id)->where('is_active', 'yes')->orderBy('id', 'DESC')->first();
    }

    public function getCompanyData($id) {
        $view['data'] = $this->where('id', $id)->with('getType')->firstOrFail();

        return $view;
    }
}
