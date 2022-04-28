<?php

namespace App\Http\Controllers\Core\Stock;

use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    protected $table = 'company_stocks';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [];
    }

    public function company(){
    	return $this->hasOne(CompanyModel::class, 'id', 'company_id');
    }
}