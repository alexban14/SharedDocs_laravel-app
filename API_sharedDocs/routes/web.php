<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function($token) {
    return view('auth.password-reset', [
        'token' => $token
    ]);
})->middleware(['guest:'.config('fortify.guard')])
  ->name('password.reset');


if( App::environment('local') ) {
    Route::get('/playground', function () {
        $user = User::factory()->make();
        Mail::to($user)->send(new WelcomeMail($user));

        return null;
    });
}

Route::get('/sanctum-cookie', function() {
    return view('sanctumApp');
});
