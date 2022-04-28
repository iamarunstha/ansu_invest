<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyQuoteModel extends Model
{
    public $timestamps = false;
    protected $table = 'quote_headings';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'display_name' => ['required', 'string'],
            'ordering'  => ['nullable', 'numeric'],
            'tab_id'    => ['required', 'exists:'.(new CompanyQuoteTabModel)->getTable().',id']
    	];

    	return $rule;
    }

    public function tab(){
        return $this->hasOne(CompanyQuoteTabModel::class, 'id', 'tab_id');
    }
}
