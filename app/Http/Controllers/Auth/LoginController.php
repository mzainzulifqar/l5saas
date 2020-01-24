<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/dashboard';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{

		$this->middleware('guest')->except('logout');
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	protected function credentials(Request $request)
	{

		return ['email' => $request->email, 'password' => $request->password, 'activated' => 1];
	}

	/**
	 * Validate the user login request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return void
	 *
	 * @throws \Illuminate\Validation\ValidationException
	 */
	protected function validateLogin(Request $request)
	{
		$attr = $request->validate([
			$this->username() => 'required|string',
			'password' => 'required|string',
		]);

		$user = User::withTrashed()->where('email', '=', $attr['email'])->first();

		if ($user && $user->trashed())
		{
			$user->restore();
			$request->session()->flash('status', 'Account Activated Successfully');
		}

		return $attr;
	}

	/**
	 * The user has been authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  mixed  $user
	 * @return mixed
	 */
	protected function authenticated(Request $request, $user)
	{
		if ($user->isTwoFactorEnabled())
		{
			 session()->put('twofactor',[
        	'user_id' => $user->id,
        	'remember' => $request->has('remember'),

        ]);

		$this->guard()->logout();

		return redirect()->to('account/twoFactor/login');
    
		}
	}

}
