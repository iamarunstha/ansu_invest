<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Core\Subscriptions\SubscriptionModel;
use App\Http\Controllers\Core\User\AdminInfoModel;

class LoginController extends Controller
{
	public function getLogin() {
		return view('backend.login');
	}

    private function saveLoggedInUser(){
        AdminInfoModel::create([
            'admin_id' => \Auth::user()->id,
            'logged_in_at' => Carbon::now(),
            'ip_address' => $this->getUserIpAddr()
        ]);
    }

    private function getUserIpAddr(){
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
    }

	public function postLogin() {
		$input = request()->all();
        $super_admin = ['email' =>  $input['email'], 'password' => $input['password'], 'group_id' => 1];
        $admin = ['email' =>  $input['email'], 'password' => $input['password'], 'group_id' => 2];

		if(\Auth::attempt($super_admin) || \Auth::attempt($admin)) {
            $this->saveLoggedInUser();
			return redirect()->route('admin-home');
        } else {
			session()->flash('friendly-error-m Invalid email or password. Please try again');
			return redirect()->route('login');
		}
	}

	public function postLogout() {
		\Auth::logout();
		return redirect()->route('login');
	}

    public function getHome() {
    	return view('backend.home');
    }

    public function getChangePassword() {
    	return view('backend.change-password');
    }

    public function postChangePassword() {
    	$input = request()->all();

    	$user = \Auth::user();

    	if(\Hash::check($input['current_password'], $user->password)) {
    		if(strlen($input['new_password']) && $input['new_password'] == $input['confirm_password']) {
    			$password = bcrypt($input['new_password']);
    			$record = \App\User::where('id', $user->id)->first();
    			$record->password = $password;
    			$record->save();
    			\Session::flash('success-msg', 'Password successfully changed');
    			return redirect()->back();
    		} else {
    			\Session::flash('friendly-error-m New password and confirm password do not match');
    			return redirect()->back();	
    		}
    	} else {
    		\Session::flash('friendly-error-m Current password does not match');
    		return redirect()->back();
    	}
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token, $message=Null)
    {
        //$permissions = (new \App\Http\Middleware\Permission)->getPermissionData();
        $user = auth('api')->user();
        //$user_id = $user->id;
        //$group_id = $user->userGroup->group_id;

        //$permissions = (new \App\Http\Middleware\Permission)->getPermissionNames($group_id, $user_id);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 600,
            'message' => $message
            //'permissions'   =>  $permissions
        ]);
    }

    public function postApiLogin() {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if(auth('api')->user()->blocked_at){
            auth('api')->logout();
            return response()->json(['error' => 'Unauthorized! Account has been blocked'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function postApiRegister() {
        $input = request()->all();
        $rules = [
            'email' =>  ['required', 'email', 'unique:'.(new \App\User)->getTable().',email'],
            'phone' =>  ['required'],
            'password'  =>  ['required'],
            'first_name'  =>  ['required'],
            'last_name' => ['required'],
        ];

        $validator = \Validator::make($input, $rules);
        if($validator->fails()) {
            $error_messages = [];
            $errors = $validator->errors();
            foreach($rules as $field_name => $value) {
                if($errors->has($field_name)) {
                    foreach($errors->get($field_name) as $e) {
                        $error_messages[$field_name][] = $e;
                    }
                }
            }
            abort(response()->json(['message' => 'There are some validation errors', 'data' => $error_messages], 422));
        } else{
            try {
                \DB::beginTransaction();
                    $user_id = \App\User::create([
                        'email' =>  $input['email'],
                        'password'  => bcrypt($input['password']),
                        'phone' =>  $input['phone'],
                        'name'   =>  $input['first_name'].' '.$input['last_name'],
                        'group_id' => 4
                    ])->id;

                    SubscriptionModel::create([
                        'user_id' => $user_id,
                        'plan_id' => 1,
                        'start_date' => Carbon::today(),
                        'expiration_date' => Carbon::today()->add('3 months')
                    ]);
                \DB::commit();

                $token = auth('api')->attempt(['email'  =>  $input['email'], 'password' =>  $input['password']], True);
                // $this->resendEmailVerification();

                return $this->respondWithToken($token, "Congratulations! You've recieved a 3-month free subscription as a trial. Thankyou for using our services.");
                            
            } catch(\Exception $e){
                session()->flash('friendly-error-mg', $e->getMessage());
                return response(['message' => $e->getMessage()], 422);
            }
        }
    }

    public function postForgotPassword() {
        $email = request()->get('email');
        $user = \App\User::where('email', $email)->first();

        if($user) {
            try {
                \DB::beginTransaction();
                    $password = \Str::random(6);
                    $user->password = bcrypt($password);
                    $user->save();
                    mail($user->email,"New Password",'Your new password is '.$password);
                \DB::commit();
                return [
                    'message'   =>  'An email has been sent to you with your new password.'
                ];
            } catch(\Exception $e) {
                
                abort(response()->json(['message' => 'Sorry something went wrong. Please try again'], 422));    
            }
        } else {
            abort(response()->json(['message' => 'User not found'], 422));
        }
    }

    public function postApiChangePassword() {
        $user = auth('api')->user();

        $input = request()->all();
        $rules = [
            'password'  =>  ['required'],
        ];

        $validator = \Validator::make($input, $rules);
        if($validator->fails()) {
            $error_messages = [];
            $errors = $validator->errors();
            foreach($rules as $field_name => $value) {
                if($errors->has($field_name)) {
                    foreach($errors->get($field_name) as $e) {
                        $error_messages[$field_name][] = $e;
                    }
                }
            }
            abort(response()->json(['message' => 'There are some validation errors', 'data' => $error_messages], 422));
        } else {
            $user->password = bcrypt($input['password']);
            $user->save();

            return [
                'message'   =>  'Password successfully changed'
            ];
        }
    }
}
