<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AdmMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->user_role_id != 1) {
                return response()->json(['status' => 'Unauthorizeds'], 401);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
