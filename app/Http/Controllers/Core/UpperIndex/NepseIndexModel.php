<?php

namespace App\Http\Controllers\Core\UpperIndex;

use Illuminate\Database\Eloquent\Model;

class NepseIndexModel extends Model
{
    protected $table = 'nepse_index';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}