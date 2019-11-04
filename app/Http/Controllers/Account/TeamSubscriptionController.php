<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamSubscriptionController extends Controller {

	public function __construct() {

	}

	/**
	 * Loading index page
	 *
	 * @return void
	 */
	public function index(Request $request) {

		$team = auth()->user()->team;
		return view('account.teams.index',get_defined_vars());
	}

	/**
	 * update team name
	 *
	 * @return void
	 */
	public function update(Request $request){
		
		$request->user()->team->update(['name' => $request->get("name")]);
		return back()->with('success','Team Name Updated Successfully');
	}
}
