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
        if (!auth('api')->check()) {
            return response()->json(new NotAuthenticatedResource($request), 401);
        }

        if (auth('api')->user()->active == 0) {
            return response()->json(new NotAuthenticatedResource($request), 401);
        }

        return $next($request);
    }
}
