<?php

namespace App\Http\Controllers\Core\Ownership;

use Illuminate\Database\Eloquent\Model;

class OwnershipTabModel extends Model
{
    protected $table = 'ownership_tabs';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}