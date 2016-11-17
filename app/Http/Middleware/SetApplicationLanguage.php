<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Illuminate\Support\Facades\Session;

class SetApplicationLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return  \Closure  $next
     
     */
    public function handle($request, Closure $next)
    {
        
        if(Session::get('lang') == "")
        {
            App::setLocale('en');
        }
        else
        {
            App::setLocale(Session::has('lang') ? Session::get('lang') : Config::get('app.locale'));
        }

        return $next($request);
    }
}
