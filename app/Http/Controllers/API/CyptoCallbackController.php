<?php

namespace App\Http\Controllers\API;

use App\Helpers\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CyptoCallbackController extends Controller
{
    public $ipn_secret = 'xxxxxxx';
    public $apiKey = 'xxxxxxxxx';

    public function check_ipn_request_is_valid()
    {
        $error_msg = "Unknown error";
        $auth_ok = false;
        $request_data = null;
        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
        $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
        $request_json = file_get_contents('php://input');
        $request_data = json_decode($request_json, true);
        ksort($request_data);
        $sorted_request_json = json_encode($request_data);
        if ($request_json !== false && !empty($request_json)) {
            $hmac = hash_hmac("sha512", $sorted_request_json, trim($this->ipn_secret));
            if ($hmac == $recived_hmac) {
                $auth_ok = true;
                $invoice = Invoice::where('ref_id', $request_data['uuid'])
                    ->where('status', 'PENDING')
                    ->first();
                if (!$invoice) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Invoice not found or already processed'
                    ]);
                }
                Log::channel('cryptomus')->info('Callback processed Success', $request_data);
                $invoice->status = $request_data['payment_status'];
                if ($request_data['payment_status'] == 'finished') {
                    $invoice->status = 'PAID';
                    $user = User::where('id', $invoice->user_id)->first();
                    $user->balance = $user->balance + $hook['payment_amount_usd'] * 15000;
                    $user->save();

                } else if ($request_data['payment_status'] == 'expired') {
                    $invoice->status = 'EXPIRED';
                }

                $invoice->paid_at = Carbon::parse($request_data['updated_at']);
                $invoice->payment_method = $request_data['pay_currency'];
                $invoice->update();

            } else {
                $error_msg = 'HMAC signature does not match';
                Log::channel('cryptomus')->info('Callback processed', $error_msg);
            }
            } else {
                $error_msg = 'Error reading POST data';
                Log::channel('cryptomus')->info('Callback processed', $error_msg);
            }
            } else {
                $error_msg = 'No HMAC signature sent.';
                Log::channel('cryptomus')->info('Callback processed', $error_msg);
            }
        }

    public function crypto_callback()
    {
        $hook = file_get_contents('php://input');
        $hook = json_decode($hook, true);

        $invoice = Invoice::where('ref_id', $hook['uuid'])->where('status', 'PENDING')->first();

        if($invoice){

            if ($hook['status'] == 'paid_over'){
                $sign = $hook["sign"];
                unset($hook["sign"]);
                $hash = md5(base64_encode(json_encode($hook, JSON_UNESCAPED_UNICODE)) . $this->apiKey);

                if (!hash_equals($hash, $sign)) {
                    return false;
                    Log::channel('cryptomus')->info(['Status' => 'false']);
                }

                $invoice->status = 'PAID';
                $user = User::where('id', $invoice->user_id)->first();
                $user->balance += $hook['payment_amount_usd'] * 15000;
                $user->save();

            } else if ($hook['status'] == 'cancel') {
                $invoice->status = 'EXPIRED';
            }

            $invoice->paid_at = Carbon::now();
            $invoice->payment_method = $hook['payer_currency'];
            $invoice->update();

            Log::channel('cryptomus')->info(['Status Success' => $hook]);

        } else {
            Log::channel('cryptomus')->info(['Status' => 'Invoice Tidak ditemukan!']);
        }
    }

    public function paypal_callback(Request $request)
    {
        $payload = $request->all();

        // You might want to verify the webhook to make sure it's genuinely from PayPal
        // $this->verifyWebhook($payload);

        if (isset($payload['event_type'])) {
            switch ($payload['event_type']) {
                case 'CHECKOUT.ORDER.COMPLETED':
                    // handle order completion
                    break;

                // handle other event types as needed
            }
        }

        // Return a response to acknowledge receipt of the event
        return response('Webhook Handled', 200);
    }


}
