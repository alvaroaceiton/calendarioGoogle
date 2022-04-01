<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarioController;
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

Route::get('/auth/redirect', function () {
    $parameters = ["access_type" => "offline"];
    return Socialite::driver('google')
    ->scopes(["https://www.googleapis.com/auth/calendar"])
    ->with($parameters)
    ->redirect();
})->name('auth_login');

Route::get('/auth/callback', function () {
    $googleUser = Socialite::driver('google')->stateless()->user();
    //dd($googleUser);
    //$user = User::where('id_google', $googleUser->id)->get();
    /*$user = User::updateOrCreate([
        'id_google' => $googleUser->id,
    ], [
        'id_google' => $googleUser->id,
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'token' => $googleUser->token,
        'refresh_token' => $googleUser->refreshToken,
        'expires_in' => $googleUser->expiresIn
    ]);*/

    //Auth::login($user);
    dd($googleUser);
});

Route::post('/login',  function (){
    return view('welcome');
});

Route::resource('calendario', CalendarioController::class);