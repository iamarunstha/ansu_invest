<?php

namespace App\Http\Controllers\Core\Ownership;

use Illuminate\Database\Eloquent\Model;

class OwnershipNameModel extends Model
{
    protected $table = 'ownership_names';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [
    		'name' => ['required']
    	];
    }
} 