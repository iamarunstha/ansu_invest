<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class TrailingReturnsTypeModel extends Model
{
    public $timestamps = false;
    protected $table = 'trailing_returns_type';
    protected $guarded = ['id'];
}