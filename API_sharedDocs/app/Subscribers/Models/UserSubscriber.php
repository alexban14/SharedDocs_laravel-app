<?php


namespace App\Subscribers\Models;

use App\Events\Models\User\UserCreated;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Events\Dispatcher;


class UserSubscriber
{
    // here we describe the methods between user events and their listeners
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, SendWelcomeEmail::class);
    }
}
