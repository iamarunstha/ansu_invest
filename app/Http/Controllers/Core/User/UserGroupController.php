<?php

namespace App\Http\Controllers\Core\User;

use App\Http\Controllers\Controller;

class UserGroupController extends Controller{

	public $view = 'Core.UserGroup.backend.';


	//Routes for Admin Groups
	public function getUserGroup(){
		$groups = AdminGroupModel::get();
		return view($this->view.'list-group')
			->with('data', $groups);
	}

	public function postUserGroupCreate(){
		$input = request()->all();

		$validator = \Validator::make($input['data'], (new AdminGroupModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

		AdminGroupModel::create(['group_name' => $input['data']['group_name']]);

		session()->flash('success-msg', 'User group successfully created');

		return redirect()->back();
	}

	public function postUserGroupDelete($id){
		$group = AdminGroupModel::where('id', $id)->firstOrFail();
		$group->delete();

		session()->flash('success-msg', 'User group successfully deleted');
		return redirect()->back();
	}

	public function postUserGroupEdit($id){
		$data = request()->all();
        $validator = \Validator::make($data['data'], (new AdminGroupModel)->getEditRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);

        } else {
            AdminGroupModel::where('id', $id)->update($data['data']);
            \Session::flash('success-msg', 'User group successfully updated');

            return redirect()->back();
        }
	}

	public function getUserGroupMember($group_id){
		$members = AdminGroupMappingModel::where('admin_group_id', $group_id)->with('admin')->get();
		$group_name = AdminGroupModel::where('id', $group_id)->first()->group_name;

		return view($this->view.'list-member')
			->with('data', $members)
			->with('group_id', $group_id)
			->with('group_name', $group_name);
	}

	public function getUserGroupMemberAdd($group_id){
		$admins = UserModel::where('group_id', 2)->with('groups')->get();
		$group_id = (int) $group_id;
		
		foreach($admins as $key => $admin){
			$groups = $admin->groups;
			if(empty($groups)){
				$admins->status = "Not a member";
			}else{
				if(in_array($group_id, $groups->pluck('id')->all())){
					$admin->status = "Already a member";
				}else{
					$admin->status = "Not a member";
				}
			}
		}

		$admins = $admins->groupBy('status');
		if(empty($admins["Not a member"])){
			$admins["Not a member"] = [];
		}
		
		return view($this->view.'add-member')
			->with('data', $admins["Not a member"])
			->with('group_id', $group_id);
	}

	public function postUserGroupMemberDelete($admin_group_mapping_id){
		$admin_group_mapping = AdminGroupMappingModel::where('id', $admin_group_mapping_id)->firstOrFail();
		$admin_group_mapping->delete();

		\Session::flash('success-msg', 'Member successfully removed');
		return redirect()->back();
	}

	public function postUserGroupMemberAdd($group_id, $user_id){
		AdminGroupMappingModel::create([
			'admin_group_id' => $group_id,
			'user_id'	=>	$user_id
		]);

		\Session::flash('success-msg', 'Member successfully added');
		return redirect()->route('admin-user-groups-members-get', ['id' => $group_id]);
	}

	public function postUserGroupMemberMultipleAdd($group_id){
		$rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = AdminGroupMappingModel::create(['admin_group_id' => $group_id, 'user_id' => $r]);
                if($response) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully added');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be added');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->route('admin-user-groups-members-get', ['id' => $group_id]);
	}
	// End of Routes for Admin Group


	// Routes for Admins
	public function getAdmins(){
		$admins = UserModel::where('group_id', 2)->get();
		return view($this->view.'admins.list-admin')
			->with('data', $admins);
	}

	public function postAdminCreate(){
		$input = request()->all();

        $validator = \Validator::make($input['data'], (new UserModel)->getAdminCreateRule());
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back();
        }

		$input['data']['group_id'] = 2;
		$input['data']['password'] = bcrypt($input['data']['password']);
		$admin = UserModel::create($input['data']);

		\Session::flash('success-msg', 'Admin successfully created');
		return redirect()->back();
	}

	public function postAdminDelete($admin_id){
		$admin = UserModel::where('id', $admin_id)->where('group_id', 2)->firstOrFail();
		$admin->delete();

		\Session::flash('success-msg', 'Admin successfully deleted');
		return redirect()->back();
	}

	public function postAdminEdit($admin_id){
		$admin = UserModel::where('id', $admin_id)->where('group_id', 2)->firstOrFail();
		$data = request()->data;

		$validator = \Validator::make($data, (new UserModel)->getAdminEditRule());
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors!');
            return redirect()->back();
        }

		foreach($data as $key=>$value){
			if($value){
				if($key == 'password'){
					$value = bcrypt($value);
				}
				$admin->$key = $value;
			}
		}
		$admin->save();

		\Session::flash('success-msg', 'Edited successfully');
		return redirect()->back();
	}
}
