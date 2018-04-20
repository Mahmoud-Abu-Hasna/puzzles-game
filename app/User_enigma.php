<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_enigma extends Model
{
    //
    public function tips(){

        return $this->hasMany('App\User_tip');

    }
}
