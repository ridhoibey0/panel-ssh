<?php

namespace App\Services\Xendit;

use Illuminate\Support\Str;
use App\Helpers\Response;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class CallbackService
{
    public function handle($attributes)
    {
        $invoice = Invoice::where('ref_id', $attributes['id'])
            ->where('status', 'PENDING')
            ->first();
    
        if ($invoice) {
            $invoice->status = $attributes['status'];
    
            if ($attributes['status'] == 'PAID') {
                $invoice->status = 'PAID';
                $this->updateUserBalance($invoice, $attributes);
            } else if ($attributes['status'] == 'EXPIRED') {
                $invoice->status = 'EXPIRED';
            }
    
            $invoice->update();
        }
        
        return true;
    }
    
    
    private function updateUserBalance(Invoice $invoice, array $attributes)
    {
        $user = $invoice->user;
        $user->balance += $invoice->amount;
        $invoice->paid_at = Carbon::parse($attributes['paid_at']);
        $invoice->payment_method = $attributes['payment_channel'];
        $user->save();
    }
}