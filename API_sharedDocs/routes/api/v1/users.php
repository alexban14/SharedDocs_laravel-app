<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;

// Route::group(['middleware' => ['auth:sanctum']]);
Route::group([
    // 'middleware' => ['auth'],
    // 'prefix' => 'heyaa',
    'as' => 'users'
], function()
{
    Route::get('/users', [UserController::class, 'index'])
        ->name('index')
        ->withoutMiddleware('auth')
    ;
    Route::get('/users/{user}',[UserController::class, 'show'])
        ->name('show')
        // apply constraints to a parameter
        // ->where('user', '[0-9]+')
        ->whereNumber('user');
    ;
    Route::post('/users', [UserController::class, 'store'])->name('store');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('destroy');
});
