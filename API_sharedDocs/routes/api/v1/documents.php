<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;


// Route::group(['middleware' => ['auth:sanctum']]);
Route::group([
    // 'middleware' => ['auth'],
    // 'prefix' => 'heyaa',
    'as' => 'documents'
], function()
{
    Route::get('/documents', [DocumentController::class, 'index'])
        ->name('index')
        ->withoutMiddleware('auth')
    ;
    Route::get('/documents/{document}',[DocumentController::class, 'show'])
        ->name('show')
        // apply constraints to a parameter
        // ->where('user', '[0-9]+')
        ->whereNumber('document')
        ->withoutMiddleware('auth')
    ;

    ;
    Route::post('/documents', [DocumentController::class, 'store'])->name('store');
    Route::patch('/documents/{document}', [DocumentController::class, 'update'])->name('update');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('destroy');
});
