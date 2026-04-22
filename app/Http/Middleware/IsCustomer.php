<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsCustomer
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
        if (Auth::check() && (Auth::user()->user_type == 'customer' && !Auth::user()->banned) || (Auth::user()?->shop && !Auth::user()->banned)) {
            return $next($request);
        }
        else{
            if(Auth::user()?->banned){
                return redirect()->route("logout");
            }
            session(['link' => url()->current()]);
            return redirect()->route('user.login');
        }
    }
}
