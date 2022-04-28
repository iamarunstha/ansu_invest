<?php

namespace App\Http\Controllers\Core\StaticPage;

use Illuminate\Database\Eloquent\Model;

class DefinitionModel extends Model
{
    protected $table = 'definitions';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}
