<?php

namespace App\Http\Controllers;

use App\Promotion;
use App\User;
use Berkayk\OneSignal\OneSignalClient;
use Illuminate\Http\Request;


class PromotionController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
        $promotions = Promotion::all();
        return view('admin.pages.promotions',compact('promotions'));
    }
    public function postNewPromotion(){
        $this->validate(request(), [
            'promotion_value' => 'required|numeric|digits_between:1,200',
            'promotion_code' => 'required|unique:promotions',
            'condition' => 'required'
        ]);
        $promotion = new Promotion();
        $promotion->promotion_value = request('promotion_value');
        $promotion->promotion_code = request('promotion_code');
        $promotion->condition = request('condition');
        $promotion->admin_id = auth()->id();
            $promotion->save();
        session()->flash('flash_message', 'Promotion was saved successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('admin.promotions');
    }
    public function postEditPromotion(){
        $this->validate(request(), [
            'promotion_id' => 'required|numeric',
            'promotion_value' => 'required|numeric|digits_between:1,200',
            'promotion_code' => 'required',
            'condition' => 'required'
        ]);
        $promotion = Promotion::find(request('promotion_id'));
        $promotion->promotion_value = request('promotion_value');
        $promotion->promotion_code = request('promotion_code');
        $promotion->condition = request('condition');
        $promotion->admin_id = auth()->id();
        $promotion->save();
        session()->flash('flash_message', 'Promotion was updated successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('admin.promotions');
    }
    public function postDeletePromotion(){
        $this->validate(request(), [
            'promotion_id' => 'required|numeric',
        ]);
        $promotion = Promotion::find(request('promotion_id'));
        $promotion->delete();
        session()->flash('flash_message', 'Promotion was deleted successfully.');
        session()->flash('message_type', 'success');
        return redirect()->route('admin.promotions');
    }
    public function activatePromotion($promotion_id){
        if(is_numeric($promotion_id)){
            $promotion = Promotion::find($promotion_id);
            $msg = 'Promotion is activated and users are notified.';
            if($promotion->is_applied){
                $promotion->is_applied = 0;
                $promotion->save();
                $msg='Promotion is deactivated.';
                $users = User::whereRaw($promotion->condition)->pluck('fcm_token');
                $users_arrays = array_chunk($users,1999);
                for($i =0;$i<count($users_arrays);$i++){
                    if(count($users)){
                        $params = [];
                        $the_users = $users_arrays[$i];
                        $params['include_player_ids'] = $the_users;
                        $contents = [
                            "en" => "The Promotion with code {$promotion->promotion_code} has ended , wait for next promotions",
                            "ar" => "العرض الخاص بالكود {$promotion->promotion_code} انتهى , حظا أوفر في العروض القادمة "
                        ];
                        $params['contents'] = $contents;
                        OneSignalClient::sendNotificationCustom($params);
                    }
                }


            }else{
                $promotion->is_applied = 1;
                $promotion->save();
                $users = User::whereRaw($promotion->condition)->pluck('fcm_token');
                $users_arrays = array_chunk($users,1999);
                for($i =0;$i<count($users_arrays);$i++){
                    $params = [];
                    $the_users = $users_arrays[$i];
                    $params['include_player_ids'] = $the_users;
                    $contents = [
                        "en" => "New Promotion {$promotion->promotion_value} coins with code {$promotion->promotion_code} is available , Good luck!",
                        "ar" => "عرض جديد  {$promotion->promotion_value}  و الكود الخاص به  {$promotion->promotion_code} , بالتوفيق!"
                    ];
                    $params['contents'] = $contents;
                    OneSignalClient::sendNotificationCustom($params);
                }

                //$target_users = User::whereRaw($promotion->condition)->pluck('fcm_token')->toArray();
            }
            session()->flash('flash_message', $msg);
            session()->flash('message_type', 'success');

        }else{
            session()->flash('flash_message', 'Please, select a valid promotion.');
            session()->flash('message_type', 'success');
        }
        return redirect()->route('admin.promotions');
    }
}
