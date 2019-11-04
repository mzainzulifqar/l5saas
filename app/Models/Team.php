<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name'];

     /**
     * Owner of team
     *
     * @return void
     */
     public function owner(){
     	
     	return $this->belongsTo(User::class);
     }
}
