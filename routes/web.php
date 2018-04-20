<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/user-referral/{referral_code}', 'HomeController@referralFromUser')->name('referralFromUser');
Route::post('/user-referral', 'HomeController@getReferral')->name('postReferralFromUser');

Route::prefix('admin')->group(function() {
    Route::get('/login', 'AdminController@adminLogin')->name('admin.login');
    Route::post('/login', 'AdminController@postAdminLogin')->name('admin.login.submit');
    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::post('/logout', 'AdminController@adminLogout')->name('admin.logout');
    Route::post('/postNewEnigma', 'EnigmaController@postAddEnigma')->name('admin.postNewEnigma');
    Route::post('/postEditEnigma', 'EnigmaController@postEditEnigma')->name('admin.postEditEnigma');
    Route::post('/postDeleteEnigma', 'EnigmaController@postDeleteEnigma')->name('admin.postDeleteEnigma');
    Route::get('/enigma/{enigma_id}/tips', 'TipController@showEnigmaTips');
    Route::get('/enigma/{enigma_id}/publish', 'EnigmaController@publishEnigma');
    Route::get('/enigmas', 'EnigmaController@index')->name('enigmas');

    Route::post('/postNewTip', 'TipController@postNewTip')->name('admin.postNewTip');
    Route::post('/postEditTip', 'TipController@postEditTip')->name('admin.postEditTip');
    Route::post('/postDeleteTip', 'TipController@postDeleteTip')->name('admin.postDeleteTip');
    Route::get('/tip/{tip_id}/publish', 'TipController@publishTip');


    Route::post('/postNewPromotion', 'PromotionController@postNewPromotion')->name('admin.postNewPromotion');
    Route::post('/postEditPromotion', 'PromotionController@postEditPromotion')->name('admin.postEditPromotion');
    Route::post('/postDeletePromotion', 'PromotionController@postDeletePromotion')->name('admin.postDeletePromotion');
    Route::get('/promotion/{promotion_id}/activate', 'PromotionController@activatePromotion');
    Route::get('/promotions', 'PromotionController@index')->name('admin.promotions');


    Route::get('/user/{user_id}/winner', 'UserController@makeWinner');
    Route::get('/users', 'UserController@showAllUsers')->name('admin.users');

    Route::get('/edit-admin-password', 'AdminController@editUserPassword')->name('viewEditUserPassword');
    Route::post('/edit-admin-password-post', 'AdminController@editUserPasswordPost')->name('editUserPassword');

});
