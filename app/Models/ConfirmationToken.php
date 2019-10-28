<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model {

	public $timestamps = false;

	protected $dates = ['expires_at'];

	protected $fillable = ['token', 'expires_at', 'user_id'];

	/**
	 * Relation to User
	 *
	 * @return void
	 */
	public function user() {

		return $this->belongsTo(User::class);
	}

	/**
	 * Check if token is expire
	 *
	 * @return void
	 */
	public function TokenExpired() {

		return \Carbon\Carbon::now()->gt($this->expires_at);

	}

	/**
	 * Defing key for Route Model Binding
	 *
	 * @return void
	 */
	public function getRouteKeyName() {

		return 'token';
	}

	/**
	 * Deleting the previous token
	 *
	 * @return void
	 */
	public static function boot() {
		parent::boot();
		static::creating(function ($token) {
			optional($token->user->confirmation)->delete();
		});
	}
}
