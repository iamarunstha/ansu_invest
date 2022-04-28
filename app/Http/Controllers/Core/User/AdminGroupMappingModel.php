<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class AdminGroupMappingModel extends Model
{
	protected $table = "admin_group_mapping";

	protected $guarded = ['id'];

	public $timestamps = false;

	public function admin(){
		return $this->belongsTo(UserModel::class, 'user_id', 'id');
	}
}
