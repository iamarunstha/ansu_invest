<?php

namespace App\Http\Controllers\Core\Dividend;

use Illuminate\Database\Eloquent\Model;

class DividendDetailModel extends Model
{
    protected $table = 'dividend_details';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}