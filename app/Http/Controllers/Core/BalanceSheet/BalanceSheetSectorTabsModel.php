<?php

namespace App\Http\Controllers\Core\BalanceSheet;

use App\Http\Controllers\Core\BalanceSheet\BalanceSheetTabsModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Database\Eloquent\Model;

class BalanceSheetSectorTabsModel extends Model
{
    protected $table = 'balance_sheet_sector_tabs';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function sector(){
    	return $this->hasOne(SectorModel::class, 'id', 'sector_id');
    }

    public function tab(){
    	return $this->hasOne(BalanceSheetTabsModel::class, 'id', 'tab_id');
    }
}