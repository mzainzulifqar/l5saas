<?php

namespace App\Models\Traits;

trait HasSubscription {
	
	/**
	 * Check if user has subscription
	 *
	 * @return void
	 */
	public function hasSubscribed() {

		return $this->subscribed('main');
	}

	/**
	 * Check if user hasNotsubscription
	 *
	 * @return void
	 */
	public function hasNotSubscribed() {

		return !$this->hasSubscribed();
	}

	/**
	 * Check if user hasCancelledSubscription
	 *
	 * @return void
	 */
	public function hasCancelledSubscription() {

		return optional($this->subscription('main'))->cancelled();
	}

	/**
	 * Check if user hasNotCancelledSubscription
	 *
	 * @return void
	 */
	public function hasNotCancelledSubscription() {

		return !$this->hasCancelledSubscription();
	}

	/**
	 *Check if the user is Customer
	 *
	 * @return void
	 */
	public function isCustomer() {

		return $this->hasCardOnFile();
	}

}
