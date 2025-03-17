<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompletedNotification extends Mailable
{
    use Queueable, SerializesModels;
    
     public $purchaseDetails;

    /**
     * Create a new message instance.
     */
    public function __construct($purchaseDetails)
    {
        $this->purchaseDetails = $purchaseDetails;
        $this->purchaseDetails['app_name'] = config('app.name');
    }


    /**
     * Get the message envelope.
     */
     
     
     public function build()
     {
         return $this->view('emails.user_buy_accounts')
             ->subject('Purchase Accounts Completed')
             ->from('support@premiumssh.net', 'VIPSSH.NET');
     }
     
}
