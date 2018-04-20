<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_tip extends Model
{
    //
    public function enigma(){
        return $this->belongsTo('App\User_enigma');
    }
}
