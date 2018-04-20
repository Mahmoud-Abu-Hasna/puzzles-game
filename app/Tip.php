<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tip extends Model
{
    //
    public function enigma(){
        return $this->belongsTo('App\Enigma');
    }
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public static function getNextUserTip($user_id , $enigma_id){
        $last_enigma_id = User_enigma::where([['user_id','=',$user_id],['enigma_id','=', $enigma_id]])->orderBy('id','desc')->first();
        $next = Tip::where([['id', '>', $last_enigma_id->tip_id],['is_published', '=', 1]])->min('id');
//        if(! is_null($next)){
//            $last_enigma_id->tip_id = $next->id;
//            $last_enigma_id->save();
//        }
        return $next;
    }
}
