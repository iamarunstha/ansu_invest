<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifySuperAdmin
{

    private $user;
    
    public function __construct(){
        if(Auth::check()) {
            $this->user = Auth::User();
        }
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->user || !$this->user->userGroup->group_name == 'Superadmin'){
            return redirect()->route('login');
        }else{
            return $next($request);
        }
    }
}
