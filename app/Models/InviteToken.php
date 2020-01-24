<?php

namespace App\Models;

use App\Models\Team;
use Illuminate\Database\Eloquent\Model;

class InviteToken extends Model
{
	//

	protected $fillable = ['email', 'token'];

	/**
	 * Deleting the existing Invite
	 *
	 * @return void
	 */
	public static function boot()
	{
		parent::boot();
		static::creating(function($token){
			$exist  = static::where('email',$token->email)->first();
			if($exist)
			{
				$exist->delete();
			}
		});
	}

	/**
	* Relation to team
	*
	* @return void
	*/
	public function team()
	{
		return $this->belongsTo(Team::class);
	}
}
