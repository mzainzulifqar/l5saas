<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\TwoFactor\TwoFactorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorAuthenticationLogin extends Controller
{
	//

	public function __construct()
	{

	}

	/**
	 * Login through ftp
	 *
	 * @return void
	 */
	public function login(Request $request)
	{

		return view('account.twofactor.index');

	}

	/**
	 * Verify Otp and Login
	 *
	 * @return void
	 */
	public function loginWithCode(Request $request, TwoFactorInterface $twofactor)
	{

		$request->setUserResolver($this->setUserResolver());

		$user = $request->user();

		if ($response = $twofactor->validateToken($user, $request->code))
		{
			// user logged in
			Auth::loginUsingId($user->id); //session('twofactor')['user_id']

			return redirect('/account')->with('success', 'Successfully logged in');

			session()->forget('twofactor');
		}
		else
		{
			return back()->with('error', 'Invalid Code');
		}

	}

	/**
	 * Set userResolver
	 *
	 * @return void
	 */
	protected function setUserResolver()
	{

		return function ()
		{
			return User::findOrFail(session('twofactor')['user_id']);
		};
	}
}
