<?php

namespace App\Http\Controllers\Core\Dividend;

use Illuminate\Database\Eloquent\Model;

class DividendTypeModel extends Model
{
    protected $table = 'dividend_type';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}