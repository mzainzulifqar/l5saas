<?php

namespace App\Http\Controllers\Auth\Activation;

use App\Events\ResendActivationLink;
use App\Http\Controllers\Controller;
use App\Models\ConfirmationToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivationController extends Controller {

	 /**
	 * View for resend activation link
	 *
	 * @return void
	 */
	 public function index(){

	 	return view('auth.activation.resend');
	 }

	 /**
	 * Sending verification link again
	 *
	 * @return void
	 */
	 public function store(Request $request){

	 	// little bit validation
	 	$request->validate(['email' => 'required|email|exists:users,email']);


	 	$user = User::where('email',$request->email)->first();


	 	if(!optional($user)->hasActivated())
	 	{
	 		event(new ResendActivationLink($user));
	 		return redirect('/login')->withStatus('Link send Successfully');
	 	}

	 	return redirect('/login')->withStatus('Already Registered!');

	 	
	 
	 }

	 /**
	 * Activation User Account
	 *
	 * @return void
	 */
	public function activateAccount(ConfirmationToken $token) {

		if ($token->TokenExpired()) {
			return redirect('/');

		}

		// activating user account
		$token->user->activateUserAccount();

		// deleting token
		$token->delete();

		// user logged in
		Auth::loginUsingId($token->user->id);

		// redirecting user to dashboard
		return redirect('/dashboard');
	}
}
