<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('callback', [InvoiceController::class, 'callback']);
Route::post('tricallback', [InvoiceController::class, 'callbackTripay']);
Route::post('ipn_callback', [App\Http\Controllers\API\CyptoCallbackController::class, 'check_ipn_request_is_valid'])->name('ipn_callback');
Route::post('crypto_callback', [App\Http\Controllers\API\CyptoCallbackController::class, 'crypto_callback'])->name('crypto_callback');
Route::post('paypal_callback', [App\Http\Controllers\API\CyptoCallbackController::class, 'paypal_callback'])->name('paypal_callback');
