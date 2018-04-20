<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'v1'], function(){
    Route::post('/user/register', 'API\WebServicesController@userRegister');
    Route::post('/user/login', 'API\WebServicesController@userLogin');
    Route::post('/user/enigma', 'API\WebServicesController@getEnigma');
    Route::post('/user/next-enigma', 'API\WebServicesController@getNextEnigma');
    Route::post('/user/next-tip-enigma', 'API\WebServicesController@getNextTipForEnigma');
    Route::post('/user/user-promotion-code', 'API\WebServicesController@usePromotionCode');
    Route::post('/user/user-answer-enigma', 'API\WebServicesController@answerEnigma');
    Route::post('/user/user-tip', 'API\WebServicesController@getTip');
    Route::post('/user/user-confirm-tip', 'API\WebServicesController@confirmTipUsage');
    Route::post('/user/logout', 'API\WebServicesController@userLogout');
    Route::post('/user/profile', 'API\WebServicesController@getUserProfile');
    Route::post('/user/complete-game', 'API\WebServicesController@markCompleted');
    Route::post('/users/top-ten-users', 'API\WebServicesController@getTopTenUsers');
    Route::post('/users/top-ten-winners', 'API\WebServicesController@getTopTenWinners');
});
