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


Route::middleware('auth:api')->get('/calendar', [ApiCalendarController::class,'index']);
Route::middleware('auth:api')->get('/calendar/{id}',[ApiCalendarController::class, 'edit']);
Route::middleware('auth:api')->post('/calendar',[ApiCalendarController::class, 'store']);
Route::middleware('auth:api')->delete('/calendar/{id}',[ApiCalendarController::class, 'destroy']);
Route::middleware('auth:api')->put('/calendar/{id}',[ApiCalendarController::class, 'update']);