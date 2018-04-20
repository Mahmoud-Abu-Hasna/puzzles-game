<?php

namespace App\Http\Controllers;

use App\User;
use App\User_Referal;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function showAllUsers(){

        $conditions[] = ['email','!=',''];
        if(! is_null(request('has_complete'))){
            $conditions[]=['has_complete', '=', request('has_complete')];
        }
        if(! is_null(request('is_winner'))){
            $conditions[]=['is_winner', '=', request('is_winner')];
        }
        if(! is_null(request('mac_address'))){
            $conditions[]=['mac_address', '=', request('mac_address')];
        }

        $users = User::where($conditions)->orderByRaw("coins DESC, referrals DESC")->paginate(50);
        return view('admin.pages.users',compact('users'));
    }

    public function makeWinner($user_id){

        if(is_numeric($user_id)){
            $user = User::find($user_id);
            if(! is_null($user)){
                if($user->is_winner){
                    session()->flash('flash_message', 'The chosen user is an old winner.');
                    session()->flash('message_type', 'danger');
                }else{
                    $user->is_winner = 1;
                    $user->save();
                    $user = User::find($user_id);
                    $users = User::pluck('fcm_token');
                    $users_arrays = array_chunk($users,1999);
                    for($i =0;$i<count($users_arrays);$i++){
                        if(count($users)){
                            $params = [];
                            $the_users = $users_arrays[$i];
                            $params['include_player_ids'] = $the_users;
                            $contents = [
                                "en" => "{$user->username} has won the game!",
                                "ar" => "{$user->username} فاز باللعبة! "
                            ];
                            $params['contents'] = $contents;
                            OneSignalClient::sendNotificationCustom($params);
                        }
                    }

                    session()->flash('flash_message', 'The chosen user is marked as winner.');
                    session()->flash('message_type', 'success');
                }
            }else {
                session()->flash('flash_message', 'the selected user does not exist.');
                session()->flash('message_type', 'danger');

            }
        }else{
            session()->flash('flash_message', 'the selected user does not exist.');
            session()->flash('message_type', 'danger');
        }
        return redirect()->route('admin.users');
    }
}
