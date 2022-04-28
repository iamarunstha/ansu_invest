<?php

Route::group(['prefix'	=>	'admin/', 'middleware' => 'can:isAuthorized', 'namespace' => '\App\Http\Controllers\Core\User'], function() {

		Route::get('/groups/', [
			'as'	=>	'admin-user-groups-get',
		 	'uses'	=>	'UserGroupController@getUserGroup'
		]);

		Route::post('/groups/create', [
			'as'	=>	'admin-user-groups-create-post',
			'uses'	=>	'UserGroupController@postUserGroupCreate'
		]);

		Route::post('groups/delete/{id}', [
			'as'	=>	'admin-user-groups-delete-post',
			'uses' 	=>	'UserGroupController@postUserGroupDelete'
		]);

		Route::post('groups/edit/{id}', [
			'as'	=>	'admin-user-groups-edit-post',
			'uses'	=>	'UserGroupController@postUserGroupEdit'
		]);

		Route::get('groups/members/{id}', [
			'as'	=>	'admin-user-groups-members-get',
			'uses'	=>	'UserGroupController@getUserGroupMember'
		]);

		Route::get('/groups/members/add/{group_id}', [
			'as'	=>	'admin-user-groups-members-add-get',
		 	'uses'	=>	'UserGroupController@getUserGroupMemberAdd'
		]);

		Route::post('/groups/members/delete/{admin_group_mapping_id}', [
			'as'	=>	'admin-user-groups-members-delete-post',
		 	'uses'	=>	'UserGroupController@postUserGroupMemberDelete'
		]);

		Route::post('/groups/members/add/{group_id}/{user_id}', [
			'as'	=>	'admin-user-groups-members-add-post',
		 	'uses'	=>	'UserGroupController@postUserGroupMemberAdd'
		]);

		Route::post('/groups/members/add-multiple/{group_id}/', [
			'as'	=>	'admin-user-groups-member-add-multiple-post',
			'uses'	=>	'UserGroupController@postUserGroupMemberMultipleAdd'
		]);


		// Admin Routes
		Route::get('/admins/', [
			'as'	=>	'admin-list-get',
			'uses'	=>	'UserGroupController@getAdmins'
		]);

		Route::post('/admins/create/', [
			'as'	=>	'admin-create-post',
			'uses'	=>	'UserGroupController@postAdminCreate'
		]);

		Route::post('/admins/delete/{admin_id}', [
			'as'	=>	'admin-delete-post',
			'uses'	=>	'UserGroupController@postAdminDelete'
		]);

		Route::post('/admins/edit/{admin_id}', [
			'as'	=>	'admin-edit-post',
			'uses'	=>	'UserGroupController@postAdminEdit'
		]);

		//Permission Routes
		Route::get('/admins/group-permissions/{group_id}/{module?}', [
			'as'	=>	'admin-user-groups-permission-get',
			'uses'	=>	'UserPermissionController@getGroupPermissions'
		]);

		Route::post('admins/group-permissions/delete/{group_id}/{permission_id}', [
			'as'	=>	'admin-group-permission-delete-post',
			'uses'	=>	'UserPermissionController@postGroupPermissionDelete'
		]);

		Route::post('admins/group-permissions/add/{group_id}/{permission_id}', [
			'as'	=>	'admin-group-permission-add-post',
			'uses'	=>	'UserPermissionController@postGroupPermissionAdd'
		]);

		Route::post('admins/group-permissions/delete-multiple/{group_id}', [
			'as'	=>	'admin-group-permission-multiple-delete-post',
			'uses'	=>	'UserPermissionController@postGroupPermissionMultipleDelete'
		]);

		Route::post('admins/group-permissions/add-multiple/{group_id}', [
			'as'	=>	'admin-group-permission-multiple-add-post',
			'uses'	=>	'UserPermissionController@postGroupPermissionMultipleAdd'
		]);


		Route::post('admins/register-permissions/', [
			'as'	=>	'admin-register-permissions-post',
			'uses'	=>	'UserPermissionController@postPermissionsRegister'
		]);
	}
);

Route::group(['prefix'	=>	'admin/', 'middleware' => 'can:isSuperAdmin', 'namespace' => '\App\Http\Controllers\Core\User'], function() {

	Route::get('user-info/admins/', [
		'as'	=>	'admin-user-info-admins-get',
		'uses'	=>	'UserInfoController@getAdminInfo'
	]);

	Route::get('user-info/admins/{admin_id}', [
		'as'	=>	'admin-user-info-admins-history-get',
		'uses'	=>	'UserInfoController@getAdminHistory'
	]);

	Route::post('user-info/admins/history/delete/{history_id}', [
		'as'	=>	'admin-user-info-admins-history-delete-post',
		'uses'	=>	'UserInfoController@postAdminHistoryDelete'
	]);

	Route::post('user-info/admins/history/delete/', [
		'as'	=>	'admin-user-info-admins-history-multiple-delete-post',
		'uses'	=>	'UserInfoController@postAdminHistoryMultipleDelete'
	]);

	Route::get('user-info/clients/{blocked?}', [
		'as'	=>	'admin-user-info-clients-get',
		'uses'	=>	'UserInfoController@getClientInfo'
	]);

	Route::post('user-info/clients/block/{id}', [
		'as'	=>	'admin-user-info-clients-block-post',
		'uses'	=>	'UserInfoController@postClientBlock'
	]);

	Route::post('user-info/clients/delete/{id}', [
		'as'	=>	'admin-user-info-clients-delete-post',
		'uses'	=>	'UserInfoController@postClientDelete'
	]);
});
