<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
	protected $table = "permissible";

	protected $guarded = ['id'];

	public $timestamps = false;

	public function groups(){
		return $this->belongsToMany(AdminGroupModel::class, PermissionMappingModel::class,'permission_id', 'admin_group_id');
	}
}