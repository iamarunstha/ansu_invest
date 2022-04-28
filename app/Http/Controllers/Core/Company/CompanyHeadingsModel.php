<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyHeadingsModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_headings';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';

    public function heading(){
        return $this->hasOne(CompanyBalanceSheetHeadingsModel::class, 'id', 'heading_id');
    }
}
