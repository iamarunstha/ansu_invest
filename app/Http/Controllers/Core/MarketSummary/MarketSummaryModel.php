<?php

namespace App\Http\Controllers\Core\MarketSummary;

use Illuminate\Database\Eloquent\Model;

class MarketSummaryModel extends Model
{
    protected $table = 'market_summary';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $core = '\App\Http\Controllers\Core\\';
}