<?php

namespace App\Models\Traits;

use App\Models\Plan;
use App\Models\Team;
use Laravel\Cashier\Subscription;

trait HasTeam {

	/**
	 * Team of User
	 *
	 * @return void
	 */
	public function team() {

		return $this->hasOne(Team::class);
	}

	/**
	 * Getting user plans
	 *
	 * @return void
	 */
	public function plans() {

		return $this->hasManyThrough
		(Plan::class, Subscription::class, 'user_id', 'gateway_id', 'id', 'stripe_plan')->orderBy('subscriptions.created_at', 'desc');
	}

	/**
	 * helper to get plans
	 *
	 * @return void
	 */
	public function plan() {

		$plan = $this->plans();
		return $plan;
	}

	/**
	 * Checking if plan is for team
	 *
	 * @return void
	 */
	public function isTeamEnabled() {

		if(isset($this->plan[0]) && $this->plan[0] != null)
		{
			return $this->plan[0]->isTeamEnabled();	
		}
		else
		{
			return false;
		}
		

	}

}