<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function referralFromUser($referral_code){
        return view('users.pages.redirect',compact('referral_code'));
    }
    public function getReferral(){
        if(!empty(request('mac_address')) && ! empty(request('referral_code'))){
            $user_referal = User_Referal::where([['mac_address','=',request('mac_address')],['referral_code','=',request('referral_code')]])->first();
            if(is_null($user_referal)){
                $user = User::where('referral_code','=', request('referral_code'))->first();
                $user->referrals = $user->referrals + 1;
                $user->save();
                $user_referal = new User_Referal();
                $user_referal->mac_address = request('mac_address');
                $user_referal->referral_code = request('referral_code');
                $user_referal->save();
            }
        }
        return redirect()->away('https://play.google.com/store?hl=ar');
    }
}
