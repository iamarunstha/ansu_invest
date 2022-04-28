<?php

namespace App\Http\Controllers\Core\Ownership;

use Illuminate\Database\Eloquent\Model;

class OwnershipColumnModel extends Model
{
    protected $table = 'ownership_columns';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}