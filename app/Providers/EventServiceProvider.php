<?php

namespace App\Providers;

use App\Events\AccountDeactivation;
use App\Events\ResendActivationLink;
use App\Events\UserActivationEvent;
use App\Listeners\AccountDeactivationListener;
use App\Listeners\CreateDefaultTeam;
use App\Listeners\SendActivationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,

		],
		UserActivationEvent::class => [
			SendActivationEmail::class,
			// CreateDefaultTeam::class
		],

		ResendActivationLink::class => [
			SendActivationEmail::class,
		],

		AccountDeactivation::class => [
			AccountDeactivationListener::class,
		],

	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot() {
		parent::boot();

		//
	}
}
