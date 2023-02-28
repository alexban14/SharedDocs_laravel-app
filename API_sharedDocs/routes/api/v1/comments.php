<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;


// Route::group(['middleware' => ['auth:sanctum']]);
Route::group([
    // 'middleware' => ['auth'],
    // 'prefix' => 'heyaa',
    'as' => 'comments'
], function()
{
    Route::get('/comments', [CommentController::class, 'index'])
        ->name('index')
        ->withoutMiddleware('auth')
    ;
    Route::get('/comments/{comment}',[CommentController::class, 'show'])
        ->name('show')
        // apply constraints to a parameter
        // ->where('user', '[0-9]+')
        ->whereNumber('comment');
    ;
    Route::post('/comments', [CommentController::class, 'store'])->name('store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('destroy');
});
