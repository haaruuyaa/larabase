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


Route::post('request','RequestController@sendRequest');
Route::post('response','RequestController@saveResponse');
Route::post('query','RequestController@sendQuery');
Route::post('payment','SenderController@sendPay');
Route::post('refund','RequestController@sendRefund');
Route::post('cancel','RequestController@sendCancel');
Route::post('reverse','RequestController@sendReverse');
Route::post('notify','TestController@notifyUrl');

Route::get('request','RequestController@request');
// Route::get('test','TestController@test');
// Route::get('concur','TestController@concurrent');
// Route::get('pool','TestController@pool');
// Route::get('test1','TestController@test1');
// Route::get('middle','TestController@testMiddle');
