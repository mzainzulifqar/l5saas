<?php

namespace App\Http\Middleware\Subscription;

use Closure;

class RedirectIfNotInactive 
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		if (!auth()->check()) {
			return $next($request);
		} elseif (auth()->user()->hasSubscribed()) {
			return redirect('/account')->with('success', 'Your are currently subscribed to a plan');
		}
		return $next($request);
	}
}
