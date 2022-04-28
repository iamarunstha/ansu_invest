<?php

namespace App\Http\Controllers\Core\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Controllers\Core\Subscriptions\SubscriptionModel;

class UserInfoController extends Controller{

	public $view = 'Core.UserGroup.backend.';

	public function getAdminInfo(){
        //Admins have user group 2 in users table. Superadmin 1
        $admins = UserModel::where('group_id', 2)->with('adminHistory')->get();

        return view($this->view.'admins.admin-info')->with('data', $admins);
    }

    public function getAdminHistory($admin_id){
        $data = UserModel::where('id', $admin_id)->with('adminHistory')->first();

        return view($this->view.'admins.admin-history')->with('data', $data);
    }

    public function postAdminHistoryDelete($history_id){
        $history = AdminInfoModel::where('id', $history_id)->firstOrFail();
        $history->delete();

        \Session::flash('success-msg', 'Info successfully deleted!');
        return redirect()->back();
    }

    public function postAdminHistoryMultipleDelete(){
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('frien`dly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDelete($id) {
        try {
            $data = AdminInfoModel::where('id', $id)->firstOrFail();
            $data->delete();
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'News could not be deleted'];
        }

        return ['status' => true, 'message' => 'News successfully deleted'];        
    }

    public function getClientInfo($blocked=Null){
        switch($blocked){
            case 'blocked': //request for blocked users
                //General clients have user group 4 in users table.
                $clients = UserModel::where('group_id', 4)->with('subscription')->whereNotNull('blocked_at')->orderBy('blocked_at', 'desc')->get();
                break;
            
            case Null: //request for non blocked users
                $clients = UserModel::where('group_id', 4)->with('subscription')->whereNull('blocked_at')->orderBy('created_at', 'desc')->get();
                break;

            default: //bad request
                \Session::flash('friendly-error-msg', 'Request not found!');
                return redirect()->back();
        }

        foreach($clients as $client){
            if($client->subscription){
                $client->subscribed_plan = [
                    "plan_name" => $client->subscription->plan->plan_name,
                    "subscription_date" => Carbon::create($client->subscription->start_date)->format('m/d/Y'),
                    "duration" => $client->subscription->plan->duration.' '.$client->subscription->plan->duration_unit,
                    "expiry_date" => Carbon::create($client->subscription->expiration_date)->format('m/d/Y')
                ];
                $client->is_subscribed = True;
            }else{
                [$client->subscribed_plan, $client->is_subscribed] = [(object) [], False];
            }
        }
        return view($this->view.'admins.client-info')->with('data', $clients)->with('blocked', $blocked);
    }

    public function postClientBlock($id){
        $client = UserModel::where('id', $id)->firstOrFail();

        $task = request()->get('task', Null);
        if($task == 'unblock'){
            $client->blocked_at = Null;
        }elseif($task == 'block'){
            $client->blocked_at = Carbon::now();
        }
        $client->save();

        \Session::flash('success-msg', 'Client successfully '.$task.'ed');
        return redirect()->back();
    }

    public function postClientDelete($id){
        $client = UserModel::where('id', $id)->firstOrFail();

        //Checking if user group is either premium or general and not admin
        if ($client->group_id == 3 || $client->group_id == 4){
            if(!empty($client->subscription)){
                SubscriptionModel::where('id', $client->subscription->id)->delete();
            }
            $client->delete();
            \Session::flash('success-msg', 'Client successfully deleted!');
        }else{
            \Session::flash('friendly-error-msg', 'Client Not Found!');
        }
        return redirect()->back();
    }
}
