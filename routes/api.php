<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {

    // only for testing
    Route::post('mail-confirm-code', 'EmailConfirmationCodeAPIController@getCodeByEmail');

    Route::post('/login', 'AuthApiController@login');
    Route::post('/sign-up', 'AuthApiController@signUp');
    Route::post('/check-credentials', 'AuthApiController@checkCredentials');
    Route::post('/reset-password', 'AuthApiController@resetPassword');
    Route::post('/mail-confirm', 'AuthApiController@mailConfirm');
    Route::get('/resend-mail-confirmation', 'AuthApiController@resendMailConfirmation');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('/access-token', 'AuthApiController@accessToken');
        Route::get('/logout', 'AuthApiController@logout');
        Route::post('/change-password', 'AuthApiController@changePassword');
        Route::get('/user', 'AuthApiController@getUser');
    });
});

Route::group(['prefix' => 'user', 'namespace' => 'User'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
            Route::post('/update-profile', 'UserAPIController@update');
            Route::get('/profile', 'UserAPIController@profile');
    });
});

Route::group(['prefix' => 'product', 'namespace' => 'Product'], function () {
    Route::get('/list', 'ProductAPIController@index');
});
