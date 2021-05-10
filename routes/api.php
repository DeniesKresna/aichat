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

Route::group(['prefix' => 'auth'],function () {
    Route::post('login', 'Api\AuthController@login');
});

Route::group(['prefix' => 'auth', 'middleware'=>["jwt"]],function () {
    Route::get('me', 'Api\AuthController@me');
    Route::get('logout', 'Api\AuthController@logout');
});


//==========================customers
Route::group(['prefix' => 'v1/customers', 'middleware'=>["jwt:admin"]],function () {
    Route::post('/', 'Api\V1\CustomerController@store');
});


//==========================campaigns
Route::group(['prefix' => 'v1/campaigns', 'middleware'=>["jwt:admin"]],function () {
    Route::post('/', 'Api\V1\CampaignController@store');
});

Route::group(['prefix' => 'v1/campaigns', 'middleware'=>["jwt"]],function () {
    Route::get('/', 'Api\V1\CampaignController@index');
});

Route::group(['prefix' => 'v1/campaigns/', 'middleware'=>["jwt:customer"]],function () {
    Route::post('/participate/{id}', 'Api\V1\CampaignController@participate');
});


//==========================transactions
Route::group(['prefix' => 'v1/transactions', 'middleware'=>["jwt:customer"]],function () {
    Route::post('/', 'Api\V1\TransactionController@store');
});