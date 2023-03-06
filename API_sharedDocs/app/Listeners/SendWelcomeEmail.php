<?php

namespace App\Listeners;

use App\Events\Models\User\UserCreated;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void // where we tell laravel what to do when the event is dispatched
    {
        Mail::to($event->user)
            ->send(new WelcomeMail($event->user));
    }
}
