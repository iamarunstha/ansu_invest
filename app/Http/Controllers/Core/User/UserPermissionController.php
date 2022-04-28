<?php

namespace App\Http\Controllers\Core\User;

use App\Http\Controllers\Controller;

class UserPermissionController extends Controller{

	public $view = 'Core.UserGroup.backend.';

	public function getGroupPermissions($group_id, $module=null){
		$modules = ModuleModel::get();
		if (!$module || !ModuleModel::where('module', $module)->exists()){
			$module = $modules[0]->module;
		}
		$permissions = PermissionModel::where('module', $module)->with('groups')->paginate(10);
		$group = AdminGroupModel::where('id', $group_id)->first();

		return view($this->view.'list-group-permission')
				->with('data', $permissions)
				->with('group', $group)
				->with('modules', $modules)
				->with('module', $module);

	}

	public function postGroupPermissionDelete($group_id, $permission_id){
		$permission = PermissionMappingModel::where('admin_group_id', $group_id)
											->where('permission_id', $permission_id)
											->firstOrFail();

		$permission->delete();

		session()->flash('success-msg', 'Permissions successfully removed');
		return redirect()->back();
	}

	public function postGroupPermissionAdd($group_id, $permission_id){
		$permission = PermissionMappingModel::firstOrCreate(['admin_group_id' => $group_id, 
												'permission_id' => $permission_id]);

		session()->flash('success-msg', 'Permissions successfully added');
		return redirect()->back();
	}

	public function postGroupPermissionMultipleDelete($group_id){
		$rids = request()->get('rid');

        if($rids) {
        	\DB::beginTransaction();
            foreach($rids as $r) {
            	// dd([$group_id, $r]);
            	try {
                   	$data = PermissionMappingModel::where('admin_group_id', $group_id)
            									->where('permission_id', $r);
            					
	            	if($data->first()){
	            		$response = $data->delete();
	            	}
            	
            	}catch(\Exception $e){
            		\Session::flash('friendly-error-msg', 'Permissions could not be deleted');
            		return redirect()->back(); 
            	}
            }
            \DB::commit();

            if(isset($response)) {
                \Session::flash('success-msg', 'Permissions successfully deleted');
            }
            
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();

	}

	public function postGroupPermissionMultipleAdd($group_id){
		$rids = request()->get('rid');

        if($rids) {
        	\DB::beginTransaction();
            foreach($rids as $r) {
            	try {
            		$permission = PermissionMappingModel::firstOrCreate([
                		'admin_group_id' => $group_id,
                		'permission_id'  =>	$r
                	]);
            	}catch(\Exception $e){
            		\Session::flash('friendly-error-msg', 'Permissions could not be added');
            		return redirect()->back();
            	}
	            
            }
            \DB::commit();

            if($permission) {
                \Session::flash('success-msg', 'Permissions successfully added');
            }

        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
		
	}

	public function postPermissionsRegister(){
		$module = request()->module;

		$routes = array_filter(collect(\Route::getRoutes())->all(), function ($route){
			$module = request()->module;
			return isset($route->action['route_group']) && $route->action['route_group'] == $module;
		});
		
		$checkModuleExist = ModuleModel::where('module', $module)->first();
		if($checkModuleExist){
			$checkModuleExist->delete();
			$operation = 'unregistered';
		}else{
			\DB::beginTransaction();

			ModuleModel::firstOrCreate(['module' => $module]);

			foreach($routes as $index => $route){
				if(isset($route->action[('permission')])) {
					PermissionModel::firstOrCreate(['name' => $route->action['permission'], 'module' => $module]);
				}
			}
			\DB::commit();
			$operation = 'registered';
		}

		session()->flash('success-msg', 'Permissions successfully '.$operation);
		return redirect()->back();
	}
}
