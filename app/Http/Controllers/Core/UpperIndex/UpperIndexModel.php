<?php

namespace App\Http\Controllers\Core\UpperIndex;

use Illuminate\Database\Eloquent\Model;

class UpperIndexModel extends Model
{
    protected $table = 'upper_nepse';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $core = '\App\Http\Controllers\Core\\';
}