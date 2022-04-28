<?php

namespace App\Http\Controllers\Core\Poll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PollController extends Controller
{

	public $view = 'Core.Poll.backend.';
    
    public function postSetPoll($company_id) {
    	PollModel::where('company_id', '!=', $company_id)

    			->update(['is_active' => 'no']);

    	$data = PollModel::firstOrNew([
    		'company_id'	=>	$company_id
    	]);

    	$data->is_active = $data->is_active == 'yes' ? 'no' : 'yes';
    	/*echo '<pre>';
    	print_r($data);
    	die();*/
    	$message = $data->is_active == 'yes' ? 'activated' : 'deactivated';

    	$data->save();

    	\Session::flash('success-msg', 'Poll successfully '.$message);
    	return redirect()->back();
    }
}