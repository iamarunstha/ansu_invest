<?php

namespace App\Http\Controllers\Core\BalanceSheet;

use Illuminate\Database\Eloquent\Model;

class BalanceSheetTabsModel extends Model
{
    protected $table = 'balance_sheet_tabs';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function childTabs(){
    	return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parentTab(){
		return $this->hasOne(self::class, 'id', 'parent_id');
    }
}
