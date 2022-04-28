<?php

namespace App\Http\Controllers\Core\Subscriptions;

use App\User;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRequestModel extends Model
{
    protected $table = 'subscription_request';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule() {

    	$rule = [
    		'bank_voucher'	=>	['required', 'image', 'mimes:jpeg,png,jpg,svg','max:2048']
    	];

    	return $rule;
    }

    public function getClient(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
