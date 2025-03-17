<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentChannel;
use App\Services\Xendit\CallbackService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Xendit\Invoice as XenditInvoice;
use Xendit\Xendit;
use Illuminate\Support\Facades\Response;
use App\Helpers\Tripay;
use Yajra\DataTables\DataTables;

class InvoiceController extends Controller
{
    private $xenditService;
    private $tripayPrivateKey;
    public function __construct()
    {
        $this->xenditService = new CallbackService();
        $this->tripayPrivateKey = env('TRIPAY_PRIVATE_KEY');
    }

    public function index(Request $request)
    {
        if (Auth()->user()->phone === null) {
            return redirect()
                ->route('profile.edit')
                ->with(['number' => 'No handphone tidak boleh kosong']);
        }
        // if ($request->ajax()) {
        // $histories = Invoice::where('user_id', Auth()->user()->id)
        //     ->orderByRaw("
        //         CASE
        //             WHEN status = 'PENDING' THEN 1
        //             WHEN status = 'PAID' THEN 2
        //             ELSE 3
        //         END
        //     ")
        //     ->get();
        // return DataTables::of($histories)
        //     ->addColumn('action', function ($history) {
        //         if ($history->status === 'EXPIRED' || $history->status === 'PAID') {
        //             return '-';
        //         }

        //         $cancelLink = route('invoice.cancel', ['invoice_id' => $history->invoice_id]);
        //          return '<a href data-url="'. $cancelLink .'" class="btn btn-sm btn-danger mx-2 cancel-invoice">Cancel</a>';
        //     })
        //     ->addColumn('user_name', function ($history) {
        //         return $history->user->name;
        //     })
        //     ->addColumn('rand', function () {
        //         return rand(1000, 9999);
        //     })
        //     ->addColumn('status', function ($history) {
        //         if ($history->status === 'PAID') {
        //             return '<span class="badge badge-success">PAID</span>';
        //         } elseif ($history->status === 'PENDING') {
        //             return '<span class="badge badge-warning">PENDING</span>';
        //         } else {
        //             return '<span class="badge badge-danger">EXPIRED</span>';
        //         }
        //     })
        //    ->addColumn('invoice_url', function ($history) {
        //         if ($history->status == 'PAID' || $history->status == 'EXPIRED') {
        //             return '-';
        //         }
        //         return '<a href="' . $history->invoice_url . '" class="btn btn-sm btn-primary mx-2">Pay Now</a>';
        //     })

        //     ->editColumn('expiry_date', function ($history) {
        //         return $history->expiry_date->format('Y-m-d H:i:s');
        //     })
        //     ->editColumn('paid_at', function ($history) {
        //         return $history->paid_at ? $history->paid_at->format('Y-m-d H:i:s') : '-';
        //     })
        //     ->rawColumns(['action', 'status', 'user_name','invoice_url','rand'])
        //     ->toJson();
        // }
        $paymentMethod = Tripay::getPaymentMethod();
        $paymentMethod = json_decode($paymentMethod, true);
        if ($paymentMethod['success']) {
            return view('pages.users.topup.index', compact('paymentMethod'));
        } else {
            return back()->with('error', 'Gagal mendapatkan channel pembayaran');
        }
    }

    public function history(Request $request)
    {
        $transactions = Invoice::where('user_id', Auth::id());
        if(!empty($request->invoice)) {
            $transactions->where("ref_id", $request->invoice);
        }
        if(!empty($request->status) && $request->status !== "ALL"){
            $transactions->where("status", $request->status);
        }
        $transactions = $transactions->orderBy('id', 'desc')->paginate(10);
        return view('pages.users.topup.history', compact('transactions'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);
        if ($validator->fails()) {
            // Get the validation errors
            $errors = $validator->errors();

            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => $errors->first(),
            ]); // 422 status code indicates unprocessable entity (validation failed)
        }

        $cek_deposite = Invoice::where('user_id', Auth()->user()->id)->where('status', 'PENDING');
        if ($cek_deposite->count() >= 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda Memiliki Deposite Pending,Silahkan selesaikan terlebih dahulu atau batalkan',
            ]);
        }

        // if ($request->bank == 'CRYPTO') {
        //     return $this->cryptoPayment($request);
        // } elseif ($request->bank == 'PAYPAL') {
        //     return $this->paypalPayment($request);
        // } else {
        //     return $this->xenditPayment($request);
        // }

        return $this->tripayPayment($request);
    }

    private function cryptoPayment($request)
    {
        $amountIDR = $request->input('amount');
        $amountUSD = $amountIDR / 15000;

        DB::beginTransaction();
        try {
            $randomNumber = random_int(0, 99999);
            $paddedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $external_id = 'INV-' . $paddedNumber;
            // Mengambil data payment channel berdasarkan selectedBank
            $paymentChannel = PaymentChannel::where('code', 'CRYPTO')->first();
            // Menghitung PPN berdasarkan jenis ppn_type
            $ppn_value = $paymentChannel->ppn_value;
            if ($paymentChannel->ppn_type === 'percentage') {
                $ppn = ($amountUSD * $ppn_value) / 100;
            } else {
                $ppn = $ppn_value;
            }
            // Menghitung total amount yang harus dibayarkan (amount + PPN)
            $totalAmount = $amountUSD + $ppn;

            $ch = curl_init();

            $data = [
                'amount' => (string) $totalAmount,
                'currency' => 'USD',
                'order_id' => $external_id,
                'url_return' => route('invoce.index', ['status' => 'success']),
                'url_callback' => 'https://youdomain.com/api/crypto_callback',
            ];

            $API_KEY = 'xxxxxxxxxxx';
            $dataString = json_encode($data);
            $sign = md5(base64_encode($dataString) . $API_KEY);

            curl_setopt($ch, CURLOPT_URL, 'https://api.cryptomus.com/v1/payment');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $headers = ['Merchant: xxxxxxxxxxxxx', 'Sign: ' . $sign, 'Content-Type: application/json'];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                throw new \Exception('Curl Error: ' . curl_error($ch));
            }
            curl_close($ch);

            $resultData = json_decode($result, true);
            if (!isset($resultData['result'])) {
                throw new \Exception('Failed to process the payment.');
            }

            Invoice::create([
                'user_id' => Auth()->user()->id,
                'ref_id' => $resultData['result']['uuid'],
                'invoice_id' => $resultData['result']['order_id'],
                'invoice_url' => $resultData['result']['url'],
                'status' => 'PENDING',
                'payment_method' => 'CRYPTO',
                'amount' => $amountIDR,
                'expiry_date' => Carbon::now()->addHours(1),
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            \Log::error('Error in cryptoPayment: ' . $th->getMessage());
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create invoice. Please try again later.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deposit successful!',
            'amount' => $totalAmount,
            'redirect' => $resultData['result']['url'],
        ]);
    }

    private function xenditPayment($request)
    {
        $amount = $request->input('amount');
        $selectedBank = $request->input('bank');
        $invoiceUrl = '';

        DB::beginTransaction();
        try {
            Xendit::setApiKey(config('xendit.key'));
            // return $this->payment();
            $randomNumber = random_int(0, 99999);
            $paddedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $external_id = 'INV-' . $paddedNumber;
            // Mengambil data payment channel berdasarkan selectedBank
            $paymentChannel = PaymentChannel::where('code', $selectedBank)->first();
            // Menghitung PPN berdasarkan jenis ppn_type
            $ppn_value = $paymentChannel->ppn_value;
            if ($paymentChannel->ppn_type === 'percentage') {
                $ppn = ($amount * $ppn_value) / 100;
            } else {
                $ppn = $ppn_value;
            }
            // Menghitung total amount yang harus dibayarkan (amount + PPN)
            $totalAmount = $amount + $ppn;

            $xenditPayload = [
                'external_id' => $external_id,
                'amount' => $totalAmount,
                'description' => 'Topup saldo ' . getSettings('site_name') . ' sebesar IDR ' . number_format($totalAmount, 0),
                'customer' => [
                    'given_names' => Auth()->user()->name,
                    'email' => Auth()->user()->email,
                    'mobile_number' => Auth()->user()->phone,
                ],
                'invoice_duration' => config('xendit.invoice_duration'),
                'success_redirect_url' => route('invoce.index', ['status' => 'success']),
                'failure_redirect_url' => route('invoce.index', ['status' => 'failure']),
                'payment_methods' => [$selectedBank],
                'currency' => 'IDR',
            ];

            $response = XenditInvoice::create($xenditPayload);
            $invoiceUrl = $response['invoice_url'];

            $invoice = Invoice::create([
                'user_id' => Auth()->user()->id,
                'ref_id' => $response['id'],
                'invoice_id' => $response['external_id'],
                'invoice_url' => $response['invoice_url'],
                'status' => $response['status'],
                'payment_method' => $selectedBank,
                'amount' => $amount,
                'expiry_date' => $response['expiry_date'],
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create invoice. Please try again later.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Deposit successful!',
            'amount' => $totalAmount,
            'redirect' => $invoiceUrl,
        ]);
    }

    public function tripayPayment(Request $request)
    {
        $nominal = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        DB::beginTransaction();
        try {
            $requestTransaction = Tripay::createTransaction($paymentMethod, $nominal);
            $response = json_decode($requestTransaction, true);
            if (!$response['success']) {
                return redirect()->back()->with('error',  $response["message"]);
            }
            $data = $response['data'];
            $invoice = Invoice::create([
                'user_id' => Auth::id(),
                'ref_id' => $data['reference'],
                'invoice_id' => $data['merchant_ref'],
                'invoice_url' => $data['checkout_url'],
                'status' => $data['status'],
                'payment_method' => $paymentMethod,
                'amount' => $nominal,
                'expiry_date' => $data['expired_time'],
            ]);
            DB::commit();
            return redirect($data['checkout_url']);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            // Return the error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create invoice. Please try again later.',
            ]);
        }
    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'status' => 'error',
                'message' => $errors->first(),
            ]);
        }

        if ($request->nominal < 15000) {
            return response()->json([
                'status' => 'error',
                'message' => 'Minimal deposite IDR. 15.000',
            ]);
        }
        $channels = PaymentChannel::orderBy('name', 'ASC')->get();
        $nominal = $request->input('nominal');
        return response()->json([
            'status' => 'success',
            'message' => $this->payment($nominal, $channels),
        ]);
    }

    public function callback(Request $request)
    {
        $callbackService = new CallbackService();
        $callbackService->handle($request->all());

        return Response::status('success')->message('Callback accepted successfully')->result();
    }

    public function callbackTripay(Request $request) {
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE');
        $json = $request->getContent();
        $signature = hash_hmac('sha256', $json, $this->tripayPrivateKey);

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
        Log::info([$invoiceId, $tripayReference]);
        if ($data->is_closed_payment === 1) {
            $invoice = Invoice::where('invoice_id', $invoiceId)->where('ref_id', $tripayReference)->where('status', 'UNPAID')->first();
            if (!$invoice) {
                return Response::json([
                    'success' => false,
                    'message' => 'No invoice found or already paid: ' . $invoiceId,
                ]);
            }
            switch ($status) {
                case 'PAID':
                    $invoice->update(['status' => 'PAID']);
                    $this->updateUserBalance($invoice, $data);
                    break;

                case 'EXPIRED':
                    $invoice->update(['status' => 'EXPIRED']);
                    break;

                case 'FAILED':
                    $invoice->update(['status' => 'FAILED']);
                    break;

                default:
                    return Response::json([
                        'success' => false,
                        'message' => 'Unrecognized payment status',
                    ]);
            }
            return Response::json(['success' => true]);
        }
    }


    public function payment($data, $channels)
    {
        $html =
            '<form id="createInvoice" action="' .
            route('invoce.store') .
            '" method="post">' .
            csrf_field() .
            '
            <div class="row">
                <div class="col-sm-12">
                    <h5 class="f-light mb-3">Pilih Metode Pembayaran <button class="btn btn-sm btn-outline-danger float-end" onclick="batalDepo()">Batal</button>
                    </h5>
                </div>
                <div class="col-sm-12">
                    <div class="list-method" style="height: 300px; overflow-x: hidden; overflow-y: auto;">
                        <style>
                            /* Hide scrollbar style for webkit-based browsers */
                            .list-method::-webkit-scrollbar {
                                width: 10px; /* Set the width of the scrollbar */
                            }

                            /* Hide scrollbar thumb */
                            .list-method::-webkit-scrollbar-thumb {
                                background: transparent;
                                border-radius: 5px;
                            }

                            /* Hide scrollbar track */
                            .list-method::-webkit-scrollbar-track {
                                background: transparent;
                            }

                            /* Optional: Show a custom scrollbar thumb color on hover */
                            .list-method::-webkit-scrollbar-thumb:hover {
                                background: #ccc;
                            }
                        </style>
                        <div class="row">'; // Start a new row for the payment methods
        foreach ($channels as $channel) {
            $html .= '
                            <div class="col-md-4 mb-3">'; // Use col-3 to maintain 4 columns per row
            $html .=
                '
                                <input type="hidden" class="form-control" name="amount" readonly value="' .
                $data .
                '">
                                <div class="light-background border b-r-8 border-primary h-100 text-center">
                                    <div class="card-body">
                                        <img width="100" src="' .
                asset('payment/' . $channel->logo) .
                '">
                                        <p class="f-light mt-3 mb-0">' .
                $channel->name .
                '</p>
                                        <p class="font-success">Fee ';

            if ($channel->ppn_type === 'percentage') {
                $html .= $channel->ppn_value . '%';
            } else {
                $html .= number_format($channel->ppn_value, 0);
            }

            $html .=
                '</p>
                                        <button type="button" class="btn btn-outline-primary bank-btn" data-bank="' .
                $channel->code .
                '">Bayar</button>
                                    </div>
                                </div>
                            </div>';
        }
        $html .= '
                        </div>
                    </div>
                </div>
            </div>
        </form>';
        return $html;
    }

    public function cancel($invoice_id)
    {
        $cancel = Invoice::where([
            'user_id' => Auth()->user()->id,
            'status' => 'PENDING',
        ])
            ->where('invoice_id', $invoice_id)
            ->first();

        if (!$cancel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invoice not found',
            ]);
        }

        $cancel->update(['status' => 'EXPIRED']);

        return response()->json([
            'status' => 'success',
            'message' => 'Deposit successfully Cancel',
        ]);
    }

    // Paypal Paymenth
    private function paypalPayment($request)
    {
        $amountIDR = $request->input('amount');
        $amountUSD = $amountIDR / 15000;

        DB::beginTransaction();
        try {
            $randomNumber = random_int(0, 99999);
            $paddedNumber = str_pad($randomNumber, 5, '0', STR_PAD_LEFT);
            $external_id = 'INV-' . $paddedNumber;

            $provider = new PayPalClient();
            $provider->setApiCredentials(config('paypal'));
            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                'intent' => 'CAPTURE',
                'application_context' => [
                    'return_url' => route('paypal.success'),
                    'cancel_url' => route('paypal.cancel'),
                ],
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => 'USD',
                            'value' => $amountUSD,
                        ],
                    ],
                ],
            ]);

            // dd($response);

            $href = collect($response['links'])->firstWhere('rel', 'approve')['href'];

            if (isset($response['id'])) {
                Invoice::create([
                    'user_id' => Auth()->user()->id,
                    'invoice_id' => $external_id,
                    'ref_id' => $response['id'],
                    'invoice_url' => $href,
                    'status' => 'PENDING',
                    'payment_method' => 'PAYPAL',
                    'amount' => $amountIDR,
                    'expiry_date' => Carbon::now()->addHours(72),
                ]);

                DB::commit();

                foreach ($response['links'] as $link) {
                    if ($link['rel'] === 'approve') {
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Deposit successful!',
                            'amount' => $amountIDR,
                            'redirect' => $link['href'],
                        ]);
                    }
                }
            } else {
                DB::rollback();
                return redirect()->route('paypal.cancel');
            }
        } catch (\Throwable $th) {
            \Log::error('Error in paypalPayment: ' . $th->getMessage());
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create invoice. Please try again later.',
            ]);
        }
    }

    public function success(Request $request)
    {
        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);

        // dd($response);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $invoice = Invoice::where('ref_id', $response['id'])->where('status', 'PENDING')->first();
            $invoice->status = 'PAID';
            $invoice->paid_at = \Carbon\Carbon::now();
            $user = User::where('id', $invoice->user_id)->first();
            $paymentValue = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $convertedValue = floatval($paymentValue) * 15000;
            $user->balance += $convertedValue;
            $user->save();
            $invoice->update();
            return redirect()->route('invoce.index', ['status' => 'success']);
        } else {
            return redirect()->route('paypal.cancel');
        }
    }

    public function cancelpaypal()
    {
        return redirect()->route('invoce.index', ['status' => 'failure']);
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
