<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

	/**
	 * index method
	 *
	 * @return void
	 */
	public function index()
	{

		$plans = Plan::active()->get();
		return view('plans.checkout', get_defined_vars());

	}

	/**
	 * Creating new subscription
	 *
	 * @return void
	 */
	public function store(CouponRequest $request)
	{

		// making a subscription
		$subscriber = auth()->user()->newSubscription('main', $request->plans);

		// checking if he has a coupon
		if ($request->has('coupon'))
		{
			$subscriber->withCoupon($request->coupon);
		}

		// making payment to stripe
		try {

			$subscriber->create($request->stripeToken);

		}
		catch (\Exception $e)
		{

			return redirect()->to('/subscription')->with('error', $e->getMessage());

		}

		if (substr($request->plans, 0, 4) == 'team')
		{

			$request->user()->team()->create(['name' => $request->user()->name]);

		}

		return redirect('/')->withStatus('Thanks for being a subscriber!');
	}

	/**
	 * Showing form for cancel Subscription
	 *
	 * @return void
	 */
	public function cancel_subscription()
	{

		return view('account.subscriptionHandler.cancelSubscription');
	}

	/**
	 * Canceling the subscription
	 *
	 * @return void
	 */
	public function cancel(Request $request)
	{

		$request->user()->subscription('main')->cancel();
		return redirect('/account')->with('success', 'Subscription has been cancelled Successfully');
	}

	/**
	 * Showing form for changing plan
	 *
	 * @return void
	 */
	public function change_subscription()
	{

		$plans = Plan::except(auth()->user()->plan[0]->id)->active()->get();
		return view('account.subscriptionHandler.changeSubscription', get_defined_vars());
	}

	/**
	 * Swapping/Changing the plan
	 *
	 * @return void
	 */
	public function changeSubscriptionPlan(Request $request)
	{

		// grabbing the user here

		$user = $request->user();

		$request->validate(['plan' => 'required|exists:plans,gateway_id']);

		$plan = Plan::where('gateway_id', $request->plan)->firstOrFail();

		if ($this->isDownGradeFromTeamPlan($user, $plan))
		{

			$user->team->users()->detach();
		}

		if (isset($user->team) && $user->team->users->count() > $plan->PlanTeamLimit())
		{

			return back()->with('error', "Remove members from Team New plan limit is" . ' ' . $plan->PlanTeamLimit());
		}

		if (!isset($user->team) && $plan->isTeamEnabled())
		{

			$request->user()->team()->create(['name' => $request->user()->name]);

		}

		$user->subscription('main')->swap($plan->gateway_id);

		return back()->with('success', 'Plan Changed Successfully');
	}

	/**
	 * Checking if the current plan
	 * is team plan before swapping
	 *
	 * @return void
	 */
	public function isDownGradeFromTeamPlan($user, Plan $plan)
	{

		if ($plan->isNotForTeam())
		{
			return true;
		}

		return false;

	}

	/**
	 * Showing form for resume Subscription
	 *
	 * @return void
	 */
	public function resume_subscription()
	{

		return view('account.subscriptionHandler.resumeSubscription');
	}

	/**
	 * Canceling the subscription
	 *
	 * @return void
	 */
	public function resume(Request $request)
	{

		$request->user()->subscription('main')->resume();
		return redirect('/account')->with('success', 'Subscription has been resumed Successfully');
	}

	/**
	 *Showing form for card update
	 *
	 * @return void
	 */
	public function update_card()
	{

		return view('account.subscriptionHandler.updateCard');
	}

	/**
	 *Showing form for card update
	 *
	 * @return void
	 */
	public function update(Request $request)
	{

		$request->user()->updateCard($request->stripeToken);
		return redirect('/account')->with('success', 'Card has been updated Successfully');
	}
}
