<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class JWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /*TODO
            remove this
        */
        //return $next($request);
        try {
            JWTAuth::parseToken()->authenticate();    
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['message' => $e->getMessage(), 'friendly-message' => 'Token Expired'], 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['message' => $e->getMessage(), 'friendly-message' => 'Token Invalid'], 401);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['message' => $e->getMessage(), 'friendly-message' => 'Token Not Present'], 422);

        }

        return $next($request);
    }
}
