<?php

namespace App\Http\Controllers\Core\Subscriptions\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Subscriptions\SubscriptionRequestModel;
use App\Http\Controllers\Core\Subscriptions\SubscriptionPlanModel;
use App\Http\Controllers\Core\Subscriptions\SubscriptionModel;
use App\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;

class ApiSubscriptionController extends Controller
{
	private $storage_folder = 'subscriptions';

	public function postSubscriptionRequest()
	{
        $data = request()->all();

        $validator = \Validator::make($data, (new SubscriptionRequestModel)->getRule());
		
		// if($validator->fails()) {
  //           return response()->json(['message' => "Image must be of type jpeg, png, jpg or svg and shouldn't exceed 2MB"], 422);
  //       }

        $user = auth('api')->user();
		
		request()->file('bank_voucher')->store($this->storage_folder, 'public');
                
        $data['bank_voucher'] = request()->file('bank_voucher')->hashName();
        
		$data['bank_voucher'] = request()->file('bank_voucher')->getClientOriginalName();
        $data['user_id'] = $user->id;
        
        $subscription_request = SubscriptionRequestModel::create($data);

		return response()->json(['data' => $subscription_request, 'message' => 'Subscription Requested!']);
	}

	public function getSubscriptionList(){
		$user = auth('api')->user();

		$subscriptions = SubscriptionModel::where('user_id', 25)->orderBy('expiration_date', 'desc')->get();

		if ($subscriptions->isEmpty()){
			$subscribed = false;
		}else{
			$subscribed = $subscriptions[0]->expiration_date > Carbon::today() ? true:false;
		}

		return response()->json(['name' => $user->name, 'subscribed' => $subscribed, 'subscriptions' => $subscriptions]);
	}

	public function getSubscriptionPlans(){
		$plans = SubscriptionPlanModel::orderBy('ordering')->get();
		foreach ($plans as $p){
			if($p->duration > 1)
				$p->duration .= " ".$p->duration_unit.'s';
			else
				$p->duration .= " ".$p->duration_unit;
			
			unset($p->duration_unit);
			unset($p->ordering);
		}

		return response()->json($plans);
	}
}
