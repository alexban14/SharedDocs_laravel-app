<?php

use App\Helpers\Routes\RouteHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')
    ->group(function()
    {
        // iterate thru the v1 folder recursively so it automatically picks up a new file with api endpoints
        RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');

        // require __DIR__ . '/api/v1/users.php';
        // require __DIR__ . '/api/v1/documents.php';
        // require __DIR__ . '/api/v1/comments.php';
    });

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
