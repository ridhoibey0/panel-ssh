<?php

namespace App\Services\Tripay;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;

class CallbackService
{
    protected $privateKey;

    public function __construct() {
        $this->privateKey = env('TRIPAY_PRIVATE_KEY');
    }

    public function handle(Request $request)
    {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $this->privateKey);

        if ($signature !== (string) $callbackSignature) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid signature',
            ]);
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return Response::json([
                'success' => false,
                'message' => 'Unrecognized callback event, no action was taken',
            ]);
        }

        $data = json_decode($json);

        if (JSON_ERROR_NONE !== json_last_error()) {
            return Response::json([
                'success' => false,
                'message' => 'Invalid data sent by tripay',
            ]);
        }

        $invoiceId = $data->merchant_ref;
        $tripayReference = $data->reference;
        $status = strtoupper((string) $data->status);

        if ($data->is_closed_payment === 1) {
            $invoice = Invoice::where('invoice_id', $invoiceId)->where('ref_id', $tripayReference)->where('status', 'PENDING')->first();
            if (!$invoice) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $invoiceId,
                ]);
            }
            switch ($status) {
                case 'PAID':
                    $invoice->update(['payment_status' => 'PAID']);
                    $this->updateUserBalance($invoice, $data);
                    break;

                case 'EXPIRED':
                    $invoice->update(['payment_status' => 'EXPIRED']);
                    break;

                case 'FAILED':
                    $invoice->update(['payment_status' => 'FAILED']);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }
            return true;
        }
    }

    private function updateUserBalance(Invoice $invoice, $attributes)
    {
        $user = $invoice->user;
        $user->balance += $invoice->amount;
        $invoice->paid_at = Carbon::parse($attributes->paid_at);
        $invoice->payment_method = $attributes->payment_method;
        $user->save();
    }
}
