<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class TrailingReturnsColumnModel extends Model
{
    public $timestamps = false;
    protected $table = 'trailing_returns_columns';
    protected $guarded = ['id'];
}