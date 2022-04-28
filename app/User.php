<?php

namespace App;

use App\Http\Controllers\Core\User\AdminGroupMappingModel;
use App\Http\Controllers\Core\User\AdminGroupModel;
use App\Http\Controllers\Core\User\UserGroupModel;
use App\Http\Controllers\Core\Subscriptions\SubscriptionModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password' ,'name', 'phone', 'group_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function userGroup(){
        return $this->hasOne(UserGroupModel::class, 'id', 'group_id');
    }

    public function groups(){
        return $this->belongsToMany(AdminGroupModel::class, AdminGroupMappingModel::class, 'user_id', 'admin_group_id');
    }

    public function subscription(){
        return $this->hasOne(SubscriptionModel::class, 'user_id', 'id');
    }
}
