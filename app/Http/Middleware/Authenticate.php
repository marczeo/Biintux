<?php

namespace app\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        // The user has not logged in yet
        if (Auth::guard($guard)->guest()) {

            if ($request->ajax()) {

                return response('Unauthorized.', 401);

            } else {

                // redirect
                return redirect()->guest('entrar');

            }
        }

        return $next($request);
    }
}