<?php

namespace App\Http\Controllers\Core\Subscriptions;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscription';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule() {

    	$rule = [

    	];

    	return $rule;
    }

    public function getClient(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function plan(){
        return $this->belongsTo(SubscriptionPlanModel::class, 'plan_id', 'id');
    }
}
