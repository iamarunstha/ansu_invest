<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyTypeModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_type';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'company_name'	=>	['required'],
    		'profile'	=>	['required'],
    		'contact'	=>	['required'],
    		'sector'	=>	['required'],
    		'industry'	=>	['required'],
    		'short_code'	=>	['required'],
    		'type_id'	=>	['required', 'exists:'.(new CompanyTypeModel)->getTable().',id']
    	];

    	return $rule;
    }

    public function getCompany() {
        return $this->hasMany(CompanyModel::class, 'type_id', 'id');
    }
}
