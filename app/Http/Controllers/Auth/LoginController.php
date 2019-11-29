<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ConfirmationToken;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
	
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
	public function __construct() {
		$this->middleware('guest')->except('logout');
	}
	
	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	protected function credentials(Request $request) {

		return ['email' => $request->email, 'password' => $request->password, 'activated' => 1];
	}
}
