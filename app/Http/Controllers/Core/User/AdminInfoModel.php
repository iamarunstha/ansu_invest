<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;

class AdminInfoModel extends Model
{
	protected $table = "admin_info";

	protected $guarded = ['id'];

    public $timestamps = false;

	public function admin(){
		return $this->belongsTo(UserModel::class, 'admin_id', 'id');
	}
}
