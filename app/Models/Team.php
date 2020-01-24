<?php

namespace App\Models;

use App\Models\Traits\HasInviteToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model {

	use HasInviteToken;

	protected $fillable = ['name'];

	/**
	 * Owner of team
	 *
	 * @return void
	 */
	public function owner() {

		return $this->belongsTo(User::class,'user_id');
	}

	/**
	 * Getting users belongsTo Specific team
	 *
	 * @return void
	 */
     public function users(){
          
          return $this->belongsToMany(User::class,'team_users')->withTimestamps();
     }

}
