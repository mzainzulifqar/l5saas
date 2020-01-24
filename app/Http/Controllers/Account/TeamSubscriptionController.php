<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Mail\Account\SendInviteEmail;
use App\Models\InviteToken;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TeamSubscriptionController extends Controller
{

	/**
	 * Loading index page
	 *
	 * @return void
	 */
	public function index(Request $request)
	{

		if (!auth()->user()->subscribed('main'))
		{
			return back();
		}

		$team = auth()->user()->team;
		return view('account.teams.index', get_defined_vars());
	}

	/**
	 * update team name
	 *
	 * @return void
	 */
	public function update(Request $request)
	{

		$request->user()->team->update(['name' => $request->get("name")]);
		return back()->with('success', 'Team Name Updated Successfully');
	}

	/**
	 * Adding members to Team
	 *
	 * @return void
	 */
	public function addMembers(Request $request, Team $team)
	{

		$request->validate(['member_email' => 'required|exists:users,email']);

		$user = User::where('email', $request->member_email)->firstOrFail();

		// checking if team limit reached

		if (auth()->user()->isTeamLimitReached())
		{

			return back()->with('error', 'Whoops! Team limit reached');
		}

		if (auth()->user()->isAlreadyOnTeam($user))
		{

			return back()->with('success', 'Member is already on the team');

		}
		else
		{

			auth()->user()->addMember($user);
			return back()->with('success', 'Member added to the Team');
		}

		abort(404);

	}

	/**
	 * Removing member from team
	 *
	 * @return void
	 */
	public function removeMember(User $user)
	{

		auth()->user()->team->users()->detach($user);

		return back()->with('success', 'Removed from team Successfully');
	}

	/**
	 * Invite team member
	 *
	 * @return void
	 */
	public function inviteMemeber(Request $request)
	{

		$request->validate(['email' => 'required']);

		$user = User::where('email', $request->email)->first();

		if (auth()->user()->isAlreadyOnTeam(optional($user)))
		{
			return back()->with('error', 'Already on Team');
		}

		if ($user)
		{
			return back()->with('error', 'Already Register Add Manually');
		}

		$token = auth()->user()->team->CreatingEntry($request->email);

		Mail::to($request->email)->send(new SendInviteEmail($token));

		return back()->with('success','Invitation Sent Successfully');
	}

	/**
	 * Accepting Invite
	 *
	 * @return void
	 */
	public function acceptinvite($token)
	{
		$token = InviteToken::where('token',$token)->firstOrFail();

		$user = User::where('email',$token->email)->first();

		if(!$user)
		{
			$newUser = User::create(['email' => $token->email,'password' => bcrypt('123456'),'activated' => true,'name' => substr($token->email,0,5)]);

			$token->team->users()->attach($newUser);

			Auth::loginUsingId($newUser->id);

			$token->delete();

			return redirect()->to('/account')->with('success','Have Fun');
		}
	

	}
}
