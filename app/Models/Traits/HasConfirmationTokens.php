<?php

namespace App\Models\Traits;

use App\Models\ConfirmationToken;

trait HasConfirmationTokens {

	/**
	 * Generating Token
	 *
	 * @return void
	 */
	public function generatingConfirmationToken() {

		$token = $this->confirmation()->create([
			'token' => $token = str_random(100),
			'expires_at' => $this->getExpireTime(),
		]);

		return $token->token;
	}

	/**
	 * Defining Expiretation time
	 *
	 * @return void
	 */
	public function getExpireTime() {

		return \Carbon\Carbon::now()->addMinutes(10);
	}

	/**
	 * Defining Relation to Token Model
	 *
	 * @return void
	 */
	public function confirmation() {

		return $this->hasOne(ConfirmationToken::class);
	}

}