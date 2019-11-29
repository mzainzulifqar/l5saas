<?php

namespace App\Http\Middleware\Subscription;

use Closure;

class IsOnPiggyBackSubscription
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

        if($request->user()->hasPiggyBackSubscription())
        {
            return back();
        }
        return $next($request);
    }
}
