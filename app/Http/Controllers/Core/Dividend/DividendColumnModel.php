<?php

namespace App\Http\Controllers\Core\Dividend;

use Illuminate\Database\Eloquent\Model;

class DividendColumnModel extends Model
{
    protected $table = 'dividend_type_columns';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}