<?php

namespace App\Listeners;

use App\Events\PurchaseCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\CompletedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PurchaseCompletedNotification
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
    public function handle(PurchaseCompleted $event)
    {
        
        try {
            Mail::to($event->purchaseDetails['email'])->send(new CompletedNotification($event->purchaseDetails));
        } catch (\Exception $e) {
            \Log::error('Error sending email: ' . $e->getMessage());
        }
    }

}
