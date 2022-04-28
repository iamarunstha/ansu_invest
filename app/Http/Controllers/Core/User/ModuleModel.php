<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class ModuleModel extends Model
{
	protected $table = "modules";

	protected $guarded = ['id'];

    public $timestamps = false;
}
