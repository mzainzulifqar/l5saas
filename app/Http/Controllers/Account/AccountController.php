<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class AccountController extends Controller {

	/**
	 * Index function
	 *
	 * @return void
	 */
	public function index() {
		return view('account.index');
	}
}
