<?php

namespace App\TwoFactor;

use App\Models\User;
use App\TwoFactor\TwoFactorInterface;
use GuzzleHttp\Client;

class Authy implements TwoFactorInterface
{

	protected $client;

	function __construct(Client $client)
	{

		$this->client = $client;
	}

	/**
	 * registering the user on AUTHY
	 *
	 * @return void
	 */
	public function register(User $user, $request)
	{

		try {

			$response = $this->client->request(
				'POST', 'https://api.authy.com/protected/json/users/new?api_key=' . config('services.authy.secret'),
				[
					'form_params' =>
					[
						'user' => $this->authyRegisterParams($user, $request),
					],
				]

			);

		}
		catch (\Exception $e)
		{

			return false;
		}

		return json_decode($response->getBody(), false);
	}

	/**
	 * Validating code on verification
	 *
	 * @return void
	 */
	public function validateToken(User $user, $token)
	{

		try {

			$response = $this->client->request(
				'GET', 'https://api.authy.com/protected/json/verify/' . $token . '/' . $user->twofactor->identifier . 'new?api_key=' . config('services.authy.secret')
			);

		}
		catch (\Exception $e)
		{

			return false;
		}

		$response = json_decode($response->getBody(), false);

		return $response->token === 'is valid';
	}

	/**
	 * Deleting the user from AUTHY
	 *
	 * @return void
	 */
	public function delete(User $user)
	{

		try {

			$response = $this->client->request(
				'POST', 'https://api.authy.com/protected/json/users/delete/' . $user->twofactor->identifier . 'new?api_key=' . config('services.authy.secret')
			);

		}
		catch (\Exception $e)
		{

			return false;
		}

		return json_decode($response->getBody(), false);

	}

	/**
	 * Form params for authy registeration
	 *
	 * @return void
	 */
	protected function authyRegisterParams($user, $request)
	{
		return [
			'email' => $user->email,
			'cellphone' => $request->number,
			'country_code' => $request->country,
		];
	}

}
