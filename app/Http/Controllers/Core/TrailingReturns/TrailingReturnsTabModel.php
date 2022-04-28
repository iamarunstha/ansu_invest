<?php

namespace App\Http\Controllers\Core\TrailingReturns;

use Illuminate\Database\Eloquent\Model;

class TrailingReturnsTabModel extends Model
{
    public $timestamps = false;
    protected $table = 'trailing_returns_tabs';
    protected $guarded = ['id'];
}