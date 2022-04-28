<?php

namespace App\Http\Controllers\Core\Investment;

use App\Http\Controllers\Core\Investment\InvestmentTabModel;
use Illuminate\Database\Eloquent\Model;

class InvestmentModel extends Model
{
    protected $table = 'investment_existing_issues';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function tab() {
    	return $this->belongsTo(InvestmentTabModel::class, 'tab_id', 'id');
    }

    public function getRule($tab_name) {
    	return [
    		'symbol' => ['required', 'string'],
    		'company_name' => ['required', 'string'],
    		'ratio' => $tab_name == 'Right Share' ? ['required', 'string'] : ['nullable'],
    		'price' => ['numeric', 'nullable'],
    		'units' => ['numeric', 'required'],
    		'opening_date' => ['date', 'required'],
    		'closing_date' => ['date', 'required'],
    		'last_closing_date' => $tab_name != 'Right Share' ? ['required', 'date'] : ['nullable'],
    		'book_closure_date' => $tab_name == 'Right Share' ? ['required', 'date'] : ['nullable'],
    		'issue_manager' => ['required', 'string'],
    		'status' => ['in:open,closed']
    	];
    }
}