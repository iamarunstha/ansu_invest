<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class AdminGroupModel extends Model
{
    protected $table = 'admin_groups';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
        return [
        	'group_name' => ['required', 'unique:'.$this->table]
        ];
    }

    public function getEditRule(){
        return [
        	'group_name' => ['required']
        ];
    }

    public function permissions(){
        return $this->belongsToMany(PermissionModel::class, PermissionMappingModel::class,'admin_group_id', 'permission_id');
    }
}
