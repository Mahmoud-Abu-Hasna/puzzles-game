<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enigma extends Model
{
    //
    public function tips(){

        return $this->hasMany('App\Tip');

    }
    public function admin(){
        return $this->belongsTo('App\Admin');
    }
    public static function getNextUserEnigma($user_id){
        $last_enigma_id = User_enigma::where('user_id','=',$user_id)->orderBy('id','desc')->first();
        $last_enigma =  Enigma::max('id');
        if(! is_null($last_enigma_id)){
            $enigma = Enigma::find($last_enigma_id->enigma_id);
            if($last_enigma_id->is_solved && $last_enigma_id->enigma_id == $last_enigma->id){
                return null;
            }else{
                if($last_enigma_id->is_solved || !$enigma->is_published){
                    $next = Enigma::where([['id', '>', $last_enigma_id->enigma_id],['is_published', '=', 1]])->min('id');
                    if(!is_null($next)){
                        $user_enigma = new User_enigma();
                        $user_enigma->user_id = $user_id;
                        $user_enigma->enigma_id = $next->id;

                        $user_enigma->tip_id = $enigma->tips()->where('charge','=','free')->first()->id;
                        $user_enigma->is_solved = 0;
                        $user_enigma->save();
                    }
                    return $next;
                }else{
                    return $enigma;
                }
            }

        }else{

            $first = Enigma::where('is_published', '=', 1)->orderBy('id','asc')->first();
            if(!is_null($first)){
                if(!is_null($first)){
                    $user_enigma = new User_enigma();
                    $user_enigma->user_id = $user_id;
                    $user_enigma->enigma_id = $first->id;

                    $user_enigma->tip_id = $first->tips()->where('charge','=','free')->first()->id;
                    $user_enigma->is_solved = 0;
                    $user_enigma->save();
                }
            }
            return $first;
        }
    }
}
