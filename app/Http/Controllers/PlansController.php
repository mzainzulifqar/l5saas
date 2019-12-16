<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class PlansController extends Controller {

	/**
	 * Showing User plans
	 *
	 * @return void
	 */
	public function index() {

		$plans = Plan::UsersPlan()->Active()->get();
		return view('plans.plans', compact('plans'));

	}

	/**
	 * Showing Team plans
	 *
	 * @return void
	 */
	public function team_plans() {

		$plans = Plan::TeamsPlan()->Active()->get();

		return view('plans.plans', compact('plans'));

	}
}
