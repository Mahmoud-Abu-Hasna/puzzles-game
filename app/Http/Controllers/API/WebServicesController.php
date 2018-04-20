<?php

namespace App\Http\Controllers\API;

use App\Enigma;
use App\Helpers\UUID;
use App\Promotion;
use App\Tip;
use App\User;
use App\User_enigma;
use App\User_Promotion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Berkayk\OneSignal\OneSignalClient;

class WebServicesController extends Controller
{
    //
    public $successStatus = 200;

    public function __construct()
    {
        $this->middleware('auth:api')->except(['userLogin', 'userRegister','getTopTenWinners','getTopTenUsers']);
    }

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function userLogin()
    {

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['status']=1;
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user_profile'] = $user;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            $success['status']= 0;
            $success['error'] = 'Unauthorised';
            return response()->json(['error' => $success], 401);
        }

    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function userRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'mac_address' => 'required',
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            $success['status']= 0;
            $success['error'] = $validator->errors();
            return response()->json(['error' => $success], 401);
        }

        $exist_user = User::where('mac_address', '=', request('mac_address'))->first();
        if (!is_null($exist_user)) {
            $success['status']= 0;
            $success['error'] = ['mac_address' => 'This Device has installed the app before,please login.'];
            return response()->json(['error' => $success], 401);
        } else {
            $user = new User();
            $user->username = request('username');
            $user->email = request('email');
            $user->password = bcrypt(request('password'));
            $user->mac_address = request('mac_address');
            $user->fcm_token = request('fcm_token');
            $user->api_token = UUID::generate(64, '\App\User', 'api_token');
            $user->referral_code = UUID::generate(16, '\App\User', 'referral_code');
            $user->save();
            $success['status']= 1;
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['user_profile'] = User::find($user->id);

            return response()->json(['success' => $success], $this->successStatus);
        }
    }

    public function getEnigma()
    {
        if (is_numeric(request('enigma_id'))) {
            $user_id = Auth::user()->id;
            $user_enigma = User_enigma::where([['user_id', '=', $user_id], ['enigma_id', '=', request('enigma_id')]])->first();
            $enigma = Enigma::find($user_enigma->enigma_id);
            if (!is_null($enigma)) {
                if ($enigma->is_published) {
                    if ($enigma->type == 'image') {
                        $enigma->enigma_value = asset($enigma->enigma_value);
                    }
                    $tip = $enigma->tips()->where('id', '=', $user_enigma->tip_id)->first();
                    if ($tip->type == 'image') {
                        $tip->tip_value = asset($tip->tip_value);
                    }
                    $enigma->tip = $tip;
                    $success['enigma'] = $enigma;
                    $success['status']= 1;
                    return response()->json(['success' => $success], $this->successStatus);
                } else {
                    $success['status']= 0;
                    return response()->json(['error' => $success], 204);
                }
            } else {
                $success['status']= 0;
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['status']= 0;
            return response()->json(['error' => $success], 204);
        }

    }

    public function getNextEnigma()
    {

        $user_id = Auth::user()->id;
        $enigma = Enigma::getNextUserEnigma($user_id);
        if (!is_null($enigma)) {
            if ($enigma->is_published) {
                if ($enigma->type == 'image') {
                    $enigma->enigma_value = asset($enigma->enigma_value);
                }
                $tip = $enigma->tips()->where('charge', '=', 'free')->first();
                if ($tip->type == 'image') {
                    $tip->tip_value = asset($tip->tip_value);
                }
                $enigma->free_tip = $tip;
                $success['enigma'] = $enigma;
                $success['status'] = 1;
                $user = User::find($user_id);
                $user->current_enigma = $enigma->id;
                $user->save();
                return response()->json(['success' => $success], $this->successStatus);
            } else {
                $success['status']=0;
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['is_last'] = true;
            $success['status'] = 1;
            return response()->json(['success' => $success], $this->successStatus);
        }

    }

    public
    function getNextTipForEnigma()
    {
        if (is_numeric(request('enigma_id'))) {
            $user_id = Auth::user()->id;
            $tip = Tip::getNextUserTip($user_id, request('enigma_id'));
            if (!is_null($tip)) {
                if ($tip->type == 'image') {
                    $tip->tip_value = asset($tip->tip_value);
                }
                if ($tip->is_published) {
                    $success['tip'] = $tip;
                    return response()->json(['success' => $success], $this->successStatus);
                } else {
                    $success['status'] = 0;
                    return response()->json(['error' => $success], 204);
                }
            } else {
                $success['status'] = 0;
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['status'] = 0;
            return response()->json(['error' => $success], 204);
        }
    }

    public
    function usePromotionCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promotion_code' => 'required|regex:[A-Za-z1-9 ]'
        ]);

        if ($validator->fails()) {
            $success['status']= 0;
            $success['error'] = $validator->errors();
            return response()->json(['error' => $success], 401);
        }
        if (is_string(request('promotion_code'))) {
            $user_id = Auth::user()->id;
            $promotion = Promotion::where('promotion_code', '=', request('promotion_code'))->first();
            if (!is_null($promotion)) {
                if ($promotion->is_applied) {
                    $user_promotion = User_Promotion::where([['promotion_id', '=', $promotion->id], ['user_id', '=', $user_id]])->first();
                    if (is_null($user_promotion)) {
                        $user = User::find($user_id);
                        $user_coins = $user->coins + $promotion->promotion_value;
                        $new_coins = $user_coins >= 500 ? 500 : $user_coins;
                        $user->coins = $new_coins;
                        $user->save();

                        $user_promotion_new = new User_Promotion();
                        $user_promotion_new->user_id = $user_id;
                        $user_promotion_new->promotion_id = $promotion->id;
                        $user_promotion_new->save();
                        $success['status']= 1;
                        $success['msg'] = "Congratulations,You have successfully got the promotion!";
                        return response()->json(['success' => $success], $this->successStatus);
                    } else {
                        $success['status']= 0;
                        $success['msg'] = "You have already got the promotion!";
                        return response()->json(['error' => $success], 204);
                    }
                } else {
                    $success['status'] = 0;
                    $success['msg'] = "The given promotion code is not valid any more.";
                    return response()->json(['error' => $success], 204);
                }


            } else {
                $success['status'] = 0;
                $success['msg'] = "The given promotion code is not valid any more.";
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['status'] = 0;
            $success['msg'] = "The given promotion code is not valid any more.";
            return response()->json(['error' => $success], 204);
        }
    }

    public
    function answerEnigma()
    {
        if (is_numeric(request('enigma_id')) && is_numeric(request('enigma_answer'))) {
            $enigma = Enigma::find(request('enigma_id'));
            if (!is_null($enigma)) {
                $user_id = Auth::user()->id;
                if ($enigma->correct_answer == request('enigma_answer')) {
                    $user_enigma = User_enigma::where([['user_id', '=', $user_id], ['enigma_id', '=', $enigma->id]])->first();
                    if (!is_null($user_enigma)) {
                        $user_enigma->is_solved = 1;
                        $user_enigma->save();
                        if ($enigma->prize) {
                            $user = User::find($user_id);
                            $user_coins = $user->coins + $enigma->prize;
                            $new_coins = $user_coins >= 500 ? 500 : $user_coins;
                            $user->coins = $new_coins;
                            $user->save();
                        }
                        $success['status'] = 1;
                        $success['msg'] = "Congratulations!";
                        return response()->json(['success' => $success], $this->successStatus);
                    } else {
                        $success['status'] = 0;
                        $success['msg'] = "Invalid!";
                        return response()->json(['error' => $success], 204);
                    }


                } else {
                    $success['status'] = 0;
                    $success['msg'] = "Not Correct!";
                    return response()->json(['error' => $success], 204);
                }
            } else {
                $success['status'] = 0;
                $success['msg'] = "Invalid!";
                return response()->json(['error' => $success], 204);

            }

        } else {
            $success['status'] = 0;
            $success['msg'] = "Invalid!";
            return response()->json(['error' => $success], 204);
        }
    }

    public function getTip(){
        if(is_numeric(request('tip_id'))) {
            $tip = Tip::find(request('tip_id'));
            if (!is_null($tip)) {
                $success['status'] = 1;
                $success['tip'] = $tip;
                return response()->json(['success' => $success], $this->successStatus);
            } else {
                $success['status'] = 0;
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['status'] = 0;
            return response()->json(['error' => $success], 204);
        }
    }

    public
    function userLogout()
    {
        $accessToken = Auth::user()->token();
//        DB::table('oauth_refresh_tokens')
//            ->where('access_token_id', $accessToken->id)
//            ->update([
//                'revoked' => true
//            ]);

        $accessToken->revoke();
        $success['status'] = 1;
        return response()->json(['success' => $success], 204);
        //return response()->json(['success'=>['result'=>'success.','logout'=>true]], $this->successStatus);
    }

    public
    function confirmTipUsage()
    {
        if (is_numeric(request('tip_id'))) {
            $user_id = Auth::user()->id;
            $tip = Tip::find(request('tip_id'));
            if (!is_null($tip)) {
                $user = User::find($user_id);
                if ($tip->discount != 0 && $user->coins != 0) {
                    if ($user->coins >= $tip->discount) {
                        $user->coins == $user->coins - $tip->discount;
                        $user->save();
                    } else {
                        $success['status'] = 0;
                        $success['msg'] = "You do not have enough coins for this tip!";
                        return response()->json(['error' => $success], 204);
                    }

                }
                $user_enigma = User_enigma::where([['user_id', '=', $user_id], ['enigma_id', '=', $tip->enigma_id]])->first();
                $user_enigma->tip_id = $tip->id;
                $user_enigma->save();
                $user = User::find($user_id);
                $success['status'] = 1;
                $success['coins'] = $user->coins;
                $success['msg'] = "Congratulations!";
                return response()->json(['success' => $success], $this->successStatus);
            } else {
                $success['status'] = 0;
                return response()->json(['error' => $success], 204);
            }
        } else {
            $success['status'] = 0;
            return response()->json(['error' => $success], 204);
        }
    }

    public
    function getUserProfile()
    {
        $success['user_profile'] = Auth::user();
        $success['status'] = 1;
        return response()->json(['success' => $success], $this->successStatus);

    }

    public function getTopTenWinners(){

            $conditions[]=['has_complete', '=', '1'];
            $conditions[]=['is_winner', '=', '1'];
            $users = User::where($conditions)->orderByRaw("updated_at DESC , coins DESC, referrals DESC")->offset(0)->limit(10)->get();
        $success['status'] = 1;
        $success['count'] = ! is_null($users) ? count($users):0 ;
        $success['users'] = $users ;
        return response()->json(['success' => $success], $this->successStatus);
    }
    public function getTopTenUsers(){

        $users = User::orderByRaw("updated_at DESC , coins DESC, referrals DESC")->offset(0)->limit(10)->get();
        $success['status'] = 1;
        $success['count'] = ! is_null($users) ? count($users):0 ;
        $success['users'] = $users ;
        return response()->json(['success' => $success], $this->successStatus);
    }
    public function markCompleted(){
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        if (!is_null($user)){
            $user->has_complete = 1;
            $user->save();
            $success['status'] = 1;
            $success['has_complete'] = 1;
            $users = User::pluck('fcm_token');
            if(count($users)){
               $users_arrays = array_chunk($users,1999);
               for($i =0;$i<count($users_arrays);$i++){
                   $params = [];
                   $the_users = $users_arrays[$i];
                   $params['include_player_ids'] = $the_users;
                   $contents = [
                       "en" => "{$user->username} has completed the game!",
                       "ar" => "{$user->username} أنهى باللعبة! "
                   ];
                   $params['contents'] = $contents;
                   OneSignalClient::sendNotificationCustom($params);
               }

            }
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            $success['status'] = 0;
            return response()->json(['error' => $success], 204);
        }
    }

}
