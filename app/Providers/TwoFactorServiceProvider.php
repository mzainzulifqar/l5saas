<?php

namespace App\Providers;


use App\TwoFactor\Authy;
use App\TwoFactor\TwoFactorInterface;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class TwoFactorServiceProvider extends ServiceProvider {

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		
	
	}

	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot()
    {
		$this->app->singleton(TwoFactorInterface::class, function () {
			return new Authy(new Client);
		});	
	}
}
