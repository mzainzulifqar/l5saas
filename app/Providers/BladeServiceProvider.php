<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider 
{
	
    /**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
	
    	Blade::if ('subscribed', function () {
			return auth()->user()->hasSubscribed();
		});

        Blade::if('noSubscription',function()
        {
            return !auth()->check() || auth()->user()->hasNotSubscribed();
        });

        Blade::if('cancelledSubscription',function (){

            return auth()->user()->hasCancelledSubscription();
        });

        Blade::if('notCancelledSubscription',function (){

            return auth()->user()->hasNotCancelledSubscription();
        });

        Blade::if('isCustomer',function (){

            return auth()->user()->isCustomer();
        });

        Blade::if('isTeamPlan',function (){

            return auth()->user()->isTeamEnabled();
        });
	}
}
