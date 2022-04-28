<?php

namespace App\Http\Controllers\Core\Investment;

use Illuminate\Database\Eloquent\Model;

class InvestmentTabModel extends Model
{
    protected $table = 'investment_existing_issues_tabs';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}