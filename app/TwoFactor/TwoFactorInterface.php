<?php  

namespace App\TwoFactor;

use App\Models\User;


interface TwoFactorInterface
{
	public function register(User $user,$request);

	public function validateToken(User $user,$token);

	public function delete(User $user);
}