<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordValidate;
use App\Mail\Account\PasswordUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller {

	/**
	 * Index function to load main view
	 *
	 * @return void
	 */
	public function index() {
		return view('account.profile');
	}

	/**
	 * Update user record
	 *
	 * @return void
	 */
	public function update(Request $request) {

		$request->user()->update($this->validateRequest());
		return redirect()->to('/account')->with('success', 'Profile has been updated');
	}

	/**
	 * Showing form for password
	 *
	 * @return void
	 */
	public function password() {
		return view('account.password');
	}

	/**
	 * Passwording updating
	 *
	 * @return void
	 */
	public function password_update(PasswordValidate $request) {

		$request->user()->update([
			'password' => bcrypt($request->password),
		]);

		Mail::to($request->user()->email)->send(new PasswordUpdate());

		return redirect()->to('/account')->with('success', 'Password has been updated');
	}

	/**
	 * Validating the input fields
	 *
	 * @return void
	 */
	private function validateRequest() {
		$attr = request()->validate([
			'name' => 'required',
			'email' => 'required|string|email|max:255|unique:users,email,' . auth()->user()->id,
		]);

		return $attr;
	}
}
