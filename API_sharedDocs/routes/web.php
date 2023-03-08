<?php

use App\Events\ChatMessageEvent;
use App\Events\PlaygroundEvent;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Mail\WelcomeMail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use function PHPSTORM_META\map;

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


// signing routes
Route::get('/shared/documents/{document}', function (Request $request, Document $document){

    return "Specially document shared with you, id: {$document->id}";

})->name('shared.document')->middleware('signed');

if( App::environment('local') ) {
    Route::get('/shared/videos/{video}', function (Request $request, $video){

//        if(!$request->hasValidSignature()){
//            abort(401);
//        }

       return 'git gud';
    })->name('share-video')->middleware('signed');

    Route::get('/signedRoute', function() {
        $url = URL::temporarySignedRoute('shared-video', now()->addSeconds(30), [
            'video' => 123
        ]);
        return $url;
    });


    // welcome email message demo
    Route::get('/welcome-mail', function () {
        $user = User::factory()->make();
        Mail::to($user)->send(new WelcomeMail($user));

        return null;
    });

    Route::get('/playground', function() {
        event(new ChatMessageEvent('Playground event'));
        return null;
    });

    Route::get('/ws', function() {
        return view('websocket');
    });

    Route::post('/chat-message', function(Request $request) {
        event(new ChatMessageEvent($request->message));
        return null;
    });
}

Route::get('/sanctum-cookie', function() {
    return view('sanctumApp');
});
