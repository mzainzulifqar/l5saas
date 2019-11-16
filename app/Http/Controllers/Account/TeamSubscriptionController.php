<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
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
		return view('account.teams.index', get_defined_vars());
	}

	/**
	 * update team name
	 *
	 * @return void
	 */
	public function update(Request $request) {

		$request->user()->team->update(['name' => $request->get("name")]);
		return back()->with('success', 'Team Name Updated Successfully');
	}

	/**
	 * Adding members to Team
	 *
	 * @return void
	 */
	public function addMembers(Request $request, Team $team) {

		$request->validate(['member_email' => 'required|exists:users,email']);

		$user = User::where('email', $request->member_email)->firstOrFail();

		// checking if team limit reached
		
		if(auth()->user()->isTeamLimitReached())
		{
			return back()->with('error','Whoops! Team limit reached');
		}

		if (auth()->user()->isAlreadyOnTeam($user)) {

			return back()->with('success', 'Member is already on the team');
		} else {

			auth()->user()->addMember($team, $user);
			return back()->with('success', 'Member added to the Team');
		}

		abort(404);

	}

	/**
	 * Removing member from team
	 *
	 * @return void
	 */
	public function removeMember(User $user){
		
		auth()->user()->team->users()->detach($user);

		return back()->with('success','Removed from team Successfully');
	}
}
