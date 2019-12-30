<?php

namespace App\Models\Traits;

trait HasSubscription
{

	/**
	 * Check if user has subscription
	 *
	 * @return void
	 */
	public function hasSubscribed()
	{

		if ($this->hasPiggyBackSubscription())
		{

			return true;
		}

		return $this->subscribed('main');
	}

	/**
	 * Check if user hasNotsubscription
	 *
	 * @return void
	 */
	public function hasNotSubscribed()
	{

		return !$this->hasSubscribed();
	}

	/**
	 * Check if the user has PiggyBackSubscription
	 *
	 * @return void
	 */
	public function hasPiggyBackSubscription()
	{

		$teams = auth()->user()->team_users;

		if ($teams)
		{
			foreach ($teams as $team)
			{
				if (!$this->team && $team->owner->hasSubscribed())
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check if user hasCancelledSubscription
	 *
	 * @return void
	 */
	public function hasCancelledSubscription()
	{

		return optional($this->subscription('main'))->cancelled();
	}

	/**
	 * Check if user hasNotCancelledSubscription
	 *
	 * @return void
	 */
	public function hasNotCancelledSubscription()
	{

		return !$this->hasCancelledSubscription();
	}

	/**
	 *Check if the user is Customer
	 *
	 * @return void
	 */
	public function isCustomer()
	{

		return $this->hasCardOnFile();
	}

	/**
	 * Check if the user is on GracePeriod
	 *
	 * @return void
	 */
	public function isOnGracePeriod()
	{
		return optional($this->subscription('main'))->onGracePeriod();
	}

	/**
	 * Check if the subscription is cancelled
	 * and is on gracePeriod
	 *
	 * @return void
	 */
	public function cancelledAndIsOnGracePeriod()
	{
		if ($this->hasCancelledSubscription() && $this->isOnGracePeriod())
		{
			return true;
		}

		return false;
	}

}
