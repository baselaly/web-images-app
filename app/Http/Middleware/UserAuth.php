<?php

namespace App\Http\Middleware;

use App\Http\Resources\Response\NotAuthenticatedResource;
use Closure;
use JWTAuth;

class UserAuth
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(new NotAuthenticatedResource($request), 401);
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(new NotAuthenticatedResource($request), 401);
        }
    }
}
