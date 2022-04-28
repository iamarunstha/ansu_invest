<?php

namespace App\Http\Controllers\Core\Ownership;

use Illuminate\Database\Eloquent\Model;

class OwnershipModel extends Model
{
    protected $table = 'company_ownership';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [
    	];
    }
}