<?php

namespace App\Http\Controllers\Core\User;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Core\Subscriptions\SubscriptionModel;

class UserModel extends Model
{
    protected $table = 'users';
    protected $guarded = ['id'];
    protected $hidden = ['password', 'remember_token'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function store($data){
    	self::create($data);
    }

    public function groups(){
    	return $this->belongsToMany(AdminGroupModel::class, AdminGroupMappingModel::class, 'user_id', 'admin_group_id');
    }

    public function getAdminCreateRule(){
        return [
            'email' => ['required', 'unique:'.$this->getTable()],
            'password'  => [
                'required',
                'min:6', 
                'regex:/[@$!%*#?&^]/', // must contain a special character]
                'regex:/[0-9]/'
            ],
            'name'  => 'required'
        ];
    }

    public function getAdminEditRule(){
        return [
            'email' => 'required',
            'password' => [
                'nullable',
                'min:6',
                'regex:/[@$!%*#?&]/', // must contain a special character]
                'regex:/[0-9]/'      // must contain at least one digit
            ],
            'name' => 'required'
        ];
    }

    public function adminHistory(){
        return $this->hasMany(AdminInfoModel::class, 'admin_id', 'id')->orderBy('logged_in_at', 'desc');
    }

    public function subscription(){
        return $this->hasOne(SubscriptionModel::class, 'user_id', 'id');
    }
}
