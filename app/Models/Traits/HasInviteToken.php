<?php

namespace App\Models\Traits;

use App\Models\InviteToken;

trait HasInviteToken
{

	/**
	 * Relation to Invite
	 *
	 * @return void
	 */
	public function invite()
	{
		return $this->hasMany(InviteToken::class);
	}

	/**
	 * Generating Token
	 *
	 * @return void
	 */
	public function CreatingEntry($email)
	{

		$invite = $this->invite()->create(['email' => $email,'token' => $token = str_random(30)]);
		return $invite->token;
	}
}