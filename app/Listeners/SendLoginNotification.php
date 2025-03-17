<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Jenssegers\Agent\Agent;
use App\Mail\UserLoggedIn;

class SendLoginNotification
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
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event): void
    {
        $agent = new Agent();
      
        $details = [
        'name' => $event->user->name,
        'email' => $event->user->email,
        'time' => now()->format('l, d-M-Y H:i:s T'),
        'ip' => request()->ip(),
        'browser' => request()->header('User-Agent'),
        'platform' => $agent->platform(),
       ];

        // Kirim email
        // Mail::to($event->user->email)->send(new UserLoggedIn($details));
    }
}
