<?php

namespace App\Http\Controllers\Core\ClientUser\Api;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;

class ApiClientUserController extends Controller
{
	public function getClientDetail(){
        $columns = ['first_name', 'last_name', 'email', 'phone', 'secondary_email', 'username', 'role', 'subscribed_plan', 'avatar', 'subscribed_to_notification', 'is_subscribed'];

        $client = request()->user();
    
        $client->name = explode(' ', $client->name);
        $client->first_name = $client->name[0];
        $client->last_name = isset($client->name[1]) ? $client->name[1] : '';
        
        [$client->subscribed_to_notification, $client->avatar] = [false, ''];
        $client->role = isset($client->userGroup) ? $client->userGroup->group_name : "General";
        
        if($client->subscription){
            $client->subscribed_plan = [
                "plan_name" => $client->subscription->plan->plan_name,
                "price" => $client->subscription->plan->price,
                "subscription_date" => Carbon::create($client->subscription->start_date)->format('m/d/Y'),
                "duration" => $client->subscription->plan->duration.' '.$client->subscription->plan->duration_unit,
                "expiry_date" => Carbon::create($client->subscription->expiration_date)->format('m/d/Y')
            ];
            $client->is_subscribed = True;
        }else{
            [$client->subscribed_plan, $client->is_subscribed] = [(object) [], False];
        }
        return $client->only($columns);
    }

    public function postClientDetail(){
        $user = request()->user();
        $input = request()->all();
        
        if(isset($input['first_name'])){
            $user->name = $input['first_name'];
            unset($input['first_name']);
            if(isset($input['last_name'])){
                $user->name .= ' '.$input['last_name'];
                unset($input['last_name']);
            }
        }

        foreach($input as $key => $value){
            if ($key == 'email' || $key == 'username') {
                if($input[$key] != $user->$key){
                    $check = User::where('id', '!=', $user->id)->where($key, $input[$key]);
                    if($check->first()){
                        abort(response()->json(['message' => $key.' already taken!'], 422));
                    }
                }
            }
            if($key == 'name'){
                continue;
            }
            $user[$key] = $input[$key];
        }
        $user->save();
        return response()->json(['message'=>"Success!", 'user' => $user]);
    }
}
