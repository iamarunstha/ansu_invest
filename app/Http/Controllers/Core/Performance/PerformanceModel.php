<?php

namespace App\Http\Controllers\Core\Performance;

use Illuminate\Database\Eloquent\Model;

class PerformanceModel extends Model
{
    protected $table = 'operating_performance';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getDownloadPerformanceRule() {

    	$rule = [
    		'start_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id'],
    		'end_fiscal_year_id' => ['required','exists:'.(new \App\Http\Controllers\Core\FiscalYear\FiscalYearModel)->getTable().',id'],
    		'start_sub_year_id'	=> ['nullable','required_with:end_sub_year_id','exists:'.(new \App\Http\Controllers\Core\Company\SubFiscalYearModel)->getTable().',id'],
    		'end_sub_year_id' => ['nullable','required_with:start_sub_year_id','exists:'.(new \App\Http\Controllers\Core\Company\SubFiscalYearModel)->getTable().',id'],
    	];

    	return $rule;
    }

    public function getPerformanceHeadingRule() {

        $rule = [
            'heading' => ['required', 'string'],
            'show_in_summary' => ['in:yes,no'],
            'style' => ['nullable', 'in:bold'],
            'in_graph' => ['required', 'in:yes,no'],
            'ordering' => ['nullable', 'numeric']
        ];

        return $rule;
    }

    public function subheadings() {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }
}