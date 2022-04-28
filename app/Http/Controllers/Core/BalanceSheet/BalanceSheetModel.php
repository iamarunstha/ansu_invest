<?php

namespace App\Http\Controllers\Core\BalanceSheet;

use Illuminate\Database\Eloquent\Model;

class BalanceSheetModel extends Model
{
    protected $table = 'balance_sheet';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}