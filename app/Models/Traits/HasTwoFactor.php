<?php 

namespace App\Models\Traits;

use App\Models\TwoFactor;

trait HasTwoFactor {

	/**
	* Relation to TwoFactor
	*
	* @return void
	*/
	public function twofactor(){
		
		return $this->hasOne(TwoFactor::class);
	}

	/**
	* TwoFactor Enabled
	*
	* @return void
	*/
	public function isTwoFactorEnabled(){
		
		return (bool)optional($this->twofactor)->isVerified();
	}

	/**
	* Check if twoFactorVerificationPending
	*
	* @return void
	*/
	public function twoFactorVerificationPending(){
		
		if(!$this->twofactor)
		{
			return false;
		}

		return !$this->twofactor->isVerified();
	}

}
