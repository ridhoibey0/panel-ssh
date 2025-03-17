<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\PurchaseCompleted;
use Illuminate\Queue\SerializesModels;

class SendPurchaseEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
       protected $purchaseDetails;

    public function __construct($purchaseDetails)
    {
        $this->purchaseDetails = $purchaseDetails;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        event(new PurchaseCompleted($this->purchaseDetails));
    }
}
