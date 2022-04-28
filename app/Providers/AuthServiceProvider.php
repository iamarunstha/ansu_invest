<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Core\User\ModuleModel;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // define a admin user role
        // returns true if user role is set to admin
        Gate::define('isSuperAdmin', function($user) {
            return $user->userGroup->group_name == 'Superadmin';
        });
    
        // define a author user role
        // returns true if user role is set to author
        Gate::define('isPremium', function($user) {
            return $user->userGroup->group_name == 'Premium';
        });

        Gate::define('isAuthorized', function($user){
            switch($user->userGroup->group_name){
                case 'Superadmin':
                    return True;
                
                case 'Admin':
                    $route = request()->route();
                    
                    $module = $route->action['route_group'];

                    if(!ModuleModel::where('module', $module)->exists())
                        return True;

                    $as = $route->action['permission'];

                    $user_groups = $user->groups;
                    foreach($user_groups as $group){
                        if(in_array($as, $group->permissions()->pluck('name')->all())){
                            return True;
                        }
                    }
                    return False;

                default:
                    return False;
            }
        });
    }
}
