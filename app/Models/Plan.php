<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {

	protected $table = "plans";

	/**
	 * Query scope for user plan
	 *
	 * @return void
	 */
	public static function scopeUsersPlan(Builder $builder) {

		return $builder->where('teams_enabled', false);
	}

	/**
	 * Query scope for excluding Current Plan
	 *
	 * @return void
	 */
	public static function scopeExcept(Builder $builder,$planId) {

		return $builder->where('id','!=',$planId);
	}

	/**
	 * Query scope for Excluding current plab
	 *
	 * @return void
	 */
	public static function scopeTeamsPlan(Builder $builder,$planId) {

		return $builder->where('id','!=', $planId);
	}

	/**
	 * Active plans
	 *
	 * @return void
	 */
	public static function scopeActive(Builder $builder) {

		return $builder->where('status', 1);
	}

	/**
	 * Checking is plan is for team
	 *
	 * @return void
	 */
	public function isTeamEnabled(){
		
		return $this->teams_enabled == true;
	}
}
