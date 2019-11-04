<?php

namespace App\Http\Middleware\Subscription\Teams;

use Closure;

class RedirectIfDoesNotHaveTeamPlan
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
        if(!auth()->user()->isTeamEnabled())
        {
            return redirect('/');
        }
        return $next($request);
    }
}
