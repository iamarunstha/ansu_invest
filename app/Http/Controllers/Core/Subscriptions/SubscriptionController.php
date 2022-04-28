<?php

namespace App\Http\Controllers\Core\Subscriptions;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Subscriptions\SubscriptionRequestModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
	public $view = 'Core.Subscriptions.backend.';
	private $storage_folder = 'subscriptions';

	public function getListView(){
		$data = SubscriptionRequestModel::where('status', 0)->with('getClient')->orderBy('uploaded_at')->get();
        return view($this->view.'list')
                ->with('data', $data);
	}

	public function postApprove($request_id){
		$request = SubscriptionRequestModel::where('id', $request_id)->first();
		$request->status = 1;
		$request->save();
		return redirect()->back();
	}

	public function postReject($request_id){
		$request = SubscriptionRequestModel::where('id', $request_id)->first();
		$request->status = 2;
		$request->save();
		return redirect()->back();
	}

	public function getSubscriptionListView(){
		$subscriptions = SubscriptionModel::with('getClient')->where('expiration_date', '>', Carbon::today())->orderBy('expiration_date', 'desc')->get();

		return view($this->view.'subscription-list')
                ->with('subscriptions', $subscriptions);
	}

	public function getSubscriptionRejectedListView(){
		$rejected_list = SubscriptionRequestModel::with('getClient')->where('status', 2)->orderBy('uploaded_at', 'desc')->get();

		return view($this->view.'rejected-list')
				->with('data', $rejected_list);
	}

	public function postDeleteView($request_id){
		SubscriptionRequestModel::where('id', $request_id)->first()->delete();
		//Delete file as well
		return redirect()->back();
	}

	public function getClientHistoryView($client_id){
		$subscription_history = SubscriptionModel::where('user_id', $client_id)->orderBy('expiration_date', 'desc')->get();

		return view($this->view.'subscription-history')
				->with('data', $subscription_history);
	}

	public function getPlansListView(){
		$plans = SubscriptionPlanModel::orderBy('ordering')->get();

		return view($this->view.'subscription-plan')
				->with('data', $plans);
	}

	public function postPlansAddView(){
        $data = request()->get('data');
		$data['slug'] = str_replace('_', '-', Str::snake($data['plan_name']));
        try {
            SubscriptionPlanModel::create($data);
            \Session::flash('success-msg', 'Link successfully added');
        }catch(\Exception $e){
            \Session::flash('friendly-error-msg', $e->getMessage());
        }
        return redirect()->back();
	}

	public function postPlansUpdateView(){
		$plans =  request()->get('data');

		\DB::beginTransaction();
		foreach ($plans as $id => $plan) {
			$plan['slug'] = str_replace('_', '-', Str::snake($plan['plan_name']));
			SubscriptionPlanModel::where('id', $id)->update($plan);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Links successfully updated');
		return redirect()->back();
	}

	public function postPlansDeleteView($id){
        $plan = SubscriptionPlanModel::where('id', $id)->firstOrFail();
		if(empty($plan->subscriptions->first())){
			$plan->delete();
			\Session::flash('success-msg', 'Subscription Plan successfully deleted');
		}
		else{
			\Session::flash('friendly-error-msg', 'Plan couldn\'t be deleted because users are already subscribed.');
		}
		return redirect()->back();
	}
}
