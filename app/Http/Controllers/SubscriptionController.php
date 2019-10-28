<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller {

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {

		$plans = Plan::active()->get();
		return view('plans.checkout', get_defined_vars());

	}

	/**
	 * Creating new subscription
	 *
	 * @return void
	 */
	public function store(CouponRequest $request) {

		$subscriber = auth()->user()->newSubscription('main', $request->plans);

		if ($request->has('coupon')) {
			$subscriber->withCoupon($request->coupon);
		}

		$subscriber->create($request->stripeToken);

		return redirect('/')->withStatus('Thanks for being a subscriber!');
	}

	/**
	 * Showing form for cancel Subscription
	 *
	 * @return void
	 */
	public function cancel_subscription() {

		return view('account.subscriptionHandler.cancelSubscription');
	}

	/**
	 * Canceling the subscription
	 *
	 * @return void
	 */
	public function cancel(Request $request) {

		$request->user()->subscription('main')->cancel();
		return redirect('/account')->with('success', 'Subscription has been cancelled Successfully');
	}

	/**
	 * Showing form for changing plan
	 *
	 * @return void
	 */
	public function change_subscription() {

		return view('account.subscriptionHandler.changeSubscription');
	}

	/**
	 * Showing form for resume Subscription
	 *
	 * @return void
	 */
	public function resume_subscription() {

		return view('account.subscriptionHandler.resumeSubscription');
	}

	/**
	 * Canceling the subscription
	 *
	 * @return void
	 */
	public function resume(Request $request) {

		$request->user()->subscription('main')->resume();
		return redirect('/account')->with('success', 'Subscription has been resumed Successfully');
	}

	/**
	 *Showing form for card update
	 *
	 * @return void
	 */
	public function update_card() {

		return view('account.subscriptionHandler.updateCard');
	}

	/**
	 *Showing form for card update
	 *
	 * @return void
	 */
	public function update(Request $request) {

		$request->user()->updateCard($request->stripeToken);
		return redirect('/account')->with('success', 'Card has been updated Successfully');
	}
}
