<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\TwoFactor\TwoFactorInterface;
use Illuminate\Http\Request;

class TwoFactorAuthenticationController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$counteries = Country::all();

		return view('account.twofactor', get_defined_vars());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, TwoFactorInterface $twofactor) {

		$request->validate([
			'country' => 'required|exists:countries,dialing_code',
			'number' => 'required|unique:two_factor,phone',
		]);

		$user = $request->user();

		if ($response = $twofactor->register($user, $request)) {

			$user->twofactor()->create([
				'phone' => $request->number,
				'dialing_code' => $request->country,
			]);

			$user->twofactor()->update([
				'identifier' => $response->user->id,
			]);
		} else {

			return back()->with('error', 'Please provide a valid number');
		}

		return back();
	}

	/**
	 * Verify the code
	 *
	 * @return void
	 */
	public function codeVerify(Request $request, TwoFactorInterface $twofactor) {

		$request->validate(['code' => 'required']);

		$user = $request->user();

		if ($response = $twofactor->validateToken($user, $request->code)) {

            $user->twofactor()->update(['verified' => true]);
		}
        else
        {
            return back()->with('error','Invalid Code');
        }

        return back();

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, TwoFactorInterface $twofactor) {
		  
        $user = $request->user();

        if ($twofactor->delete($user)) {
            
            $user->twofactor()->delete();
        }

        return back();

	}
}
