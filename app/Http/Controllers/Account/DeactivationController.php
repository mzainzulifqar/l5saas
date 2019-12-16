<?php

namespace app\Http\Controllers\Account;

use App\Events\AccountDeactivation;
use App\Http\Controllers\Controller;
use App\Rules\CurrentPassword;
use Illuminate\Http\Request;

class DeactivationController extends Controller 

{
	public function __construct() {
		$this->middleware('auth');
	}

	/**
	 *
	 *
	 * @return void
	 */
	public function index() {

		return view('account.deactivate');
	}

	/**
	 * Deactive the account
	 *
	 * @return void
	 */
	public function deactivate(Request $request) {
		
		// validation
		$user = $request->user();

		$request->validate(['current_password' => ['required', new CurrentPassword()]]);

		if($user->subscription('main'))
		{
			$user->subscription('main')->cancel();
		}

		event(new AccountDeactivation($user));
		
		$user->delete();

		return redirect('/')->with('status','Account Deactivated Successfully');
	}
}
