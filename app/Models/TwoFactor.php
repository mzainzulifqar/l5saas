<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactor extends Model
{
    protected $table = 'two_factor';

    protected $fillable = ['phone','dialing_code','verified','identifier'];


    /**
    * Relation to user
    *
    * @return void
    */
    public function user(){

    	return $this->belongsTo(User::class,'user_id');
    }

    /**
    * Event on Model
    *
    * @return void
    */
    public static function boot(){
    	
    	parent::boot();
    	static::creating(function($twofactor)
    	{
    		optional($twofactor->user->twofactor)->delete();
    	});
    }

    /**
    * Check if the status is verified
    *
    * @return void
    */
    public function isVerified(){
    	
    	return $this->verified;
    }

}
