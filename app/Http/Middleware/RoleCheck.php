<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $access)
    {
        $allow = false;
        $access = explode('|', $access);
        $roles = json_decode($request->user()->roles);

        foreach ($roles as $key => $roleCode) {
            if (in_array($roleCode, $access)) {
                $allow = true;
            }
        }

        if (!$allow) {
            return abort(403);
        }

        return $next($request);
    }
}
