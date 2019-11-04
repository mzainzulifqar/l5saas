<?php

namespace App\Models;

use App\Models\Traits\HasConfirmationTokens;
use App\Models\Traits\HasSubscription;
use App\Models\Traits\HasTeam;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable {

	use Notifiable, HasConfirmationTokens, Billable, HasSubscription,HasTeam;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'email_verified_at', 'activated', 'stripe_id',
		'card_brand', 'card_last_four', 'trial_ends_at',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/**
	 * Activating User Account
	 *
	 * @return void
	 */
	public function activateUserAccount() {

		$this->update([
			'email_verified_at' => now(),
			'activated' => 1,
		]);

	}

	/**
	 * checking if the account is activated
	 *
	 * @return void
	 */
	public function hasActivated() {
		return !!$this->activated;
	}

	
}
