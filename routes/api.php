<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiCalendarController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:api')->get('/calendar','App\Http\Controllers\ApiCalendarController@index');
Route::middleware('auth:api')->get('/calendar/{id}','App\Http\Controllers\ApiCalendarController@edit');
Route::middleware('auth:api')->post('/calendar','App\Http\Controllers\ApiCalendarController@store');
Route::middleware('auth:api')->delete('/calendar/{id}','App\Http\Controllers\ApiCalendarController@destroy');
Route::middleware('auth:api')->put('/calendar/{id}','App\Http\Controllers\ApiCalendarController@update');