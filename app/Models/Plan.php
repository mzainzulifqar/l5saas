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
	 * Query scope for Team plan
	 *
	 * @return void
	 */
	public static function scopeTeamsPlan(Builder $builder) {

		return $builder->where('teams_enabled', true);
	}

	/**
	 * Active plans
	 *
	 * @return void
	 */
     public static function scopeActive(Builder $builder){
          
          return $builder->where('status',1);
     }
}
