<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Auth;

class Tripay
{
    public static function getPaymentMethod()
    {
        $apiKey = env('TRIPAY_API_KEY');
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_URL => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR => false,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        return empty($error) ? $response : $error;
    }

    public static function createTransaction($method, $amount)
    {
        $apiKey = env('TRIPAY_API_KEY');
        $privateKey = env('TRIPAY_PRIVATE_KEY');
        $merchantCode = env('TRIPAY_MERCHANT_CODE');
        $merchantRef = 'INV-' . time();
        $data = [
            'method' => $method,
            'merchant_ref' => $merchantRef,
            'amount' => $amount,
            'customer_name' => Auth::user()->name,
            'customer_email' => Auth::user()->email,
            'customer_phone' => Auth::user()->phone,
            'order_items' => [
                [
                    'sku' => 'TOPUP',
                    'name' => 'TOPUP SALDO',
                    'price' => $amount,
                    'quantity' => 1
                ],
            ],

            'expired_time' => time() + 24 * 60 * 60, // 24 jam
            'signature' => hash_hmac('sha256', $merchantCode. $merchantRef . $amount, $privateKey),
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_URL => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey],
            CURLOPT_FAILONERROR => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);


        return $response ? $response : $error;
    }
}
