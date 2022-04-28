<?php

namespace App\Http\Controllers\Core\Sector;

use App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel;
use App\Http\Controllers\Core\TrailingReturns\SectorTrailingReturnsModel;
use Illuminate\Database\Eloquent\Model;

class SectorModel extends Model
{
    protected $table = 'company_sector';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function trailingReturns() {
        return $this->hasMany(SectorTrailingReturnsModel::class, 'sector_id', 'id');
    }

    public function tabs(){
    	return $this->hasMany(BalanceSheetSectorTabsModel::class, 'sector_id', 'id');
    }
}