<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;

class ViewShareVariablesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $notificationCount = Auth::check()?$this->getCachedNotificationCount():0;
        View::share("notificationCount", $notificationCount);
        return $next($request);
    }

    public function getCachedNotificationCount() {
        // return Cache::remember("notificationCount",5, function(){
            return $this->_getNotificationCount();
        // });
    }

    public function _getNotificationCount() {
        // todo select products that has not null sold status
        return Auth::user()->product_bids()
        ->whereHas("chats",
            function ($query) {
                return $query->where(["viewed" => 0,
                "receiver" => auth()->id()
            ]);
            })
        ->whereHas("product",function ($query) {
            return $query->whereNull('sold_status');
        })->count();
    }
}
