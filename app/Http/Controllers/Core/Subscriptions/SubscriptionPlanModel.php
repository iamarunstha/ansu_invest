<?php

namespace App\Http\Controllers\Core\Subscriptions;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanModel extends Model
{
    protected $table = 'subscription_plans';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function subscriptions(){
        return $this->hasMany(SubscriptionModel::class, 'plan_id', 'id');
    }
}
