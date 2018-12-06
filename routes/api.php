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

Route::group([
    'prefix' => 'auth'
], function ($router) {

    Route::post('google-login', 'AuthController@loginByGoogle');
    Route::post('email-login', 'AuthController@loginByEmail');
    Route::post('email-confirm', 'AuthController@emailConfirm');
    Route::get('refresh', 'AuthController@refresh');

});