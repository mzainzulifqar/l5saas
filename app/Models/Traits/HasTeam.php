<?php

namespace App\Models\Traits;

use App\Models\Plan;
use App\Models\Team;
use App\Models\User;
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
	 * Getting team users
	 *
	 * @return void
	 */
	 public function team_users(){
	 	
	 	return $this->belongsToMany(Team::class,'team_users')->withTimestamps();
	 }

	 /**
	 * Checking if the user already member 
	 * of this team
	 *
	 * @return void
	 */
	 public function isAlreadyOnTeam($user){


	 	return $this->team->users->contains('id',$user->id);
	 }

	 /**
	 * Adding user to team
	 *
	 * @return void
	 */
	 public function addMember(Team $team,User $user){
	 	
	 	return $this->team->users()->attach($user);
	 }

	/**
	* Removing member from team
	*
	* @return void
	*/
	public function removeMember(Team $team,User $user){
		
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

	 /**
	 * Checking if Team Limit reached
	 *
	 * @return void
	 */
	 public function isTeamLimitReached(){
	 	
	 	if(auth()->user()->team->users()->count() == auth()->user()->plan[0]->team_limit)
	 	{
	 		return true;
	 	}
	 	else
	 	{
	 		return false;
	 	}

	 }


	 /**
	 * Team Limit helper
	 *
	 * @return void
	 */
	 public function PlanTeamLimit(){
	 	
	 	return $this->plan[0]->team_limit;
	 	

	 }

}