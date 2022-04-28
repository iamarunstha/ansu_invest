<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class UserGroupModel extends Model
{
    protected $table = 'user_groups';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}
