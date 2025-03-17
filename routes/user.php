<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\AccountsController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\GiftCodeController;
use App\Http\Controllers\User\InvoiceController;
use App\Http\Controllers\User\LicenseController;
use App\Http\Controllers\User\ServerController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\RequestLimit;
use Illuminate\Support\Facades\Route;
use App\Models\Invoice;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:customer|reseller'])->name('dashboard');

Route::middleware(['auth', 'role:customer|reseller'])->group(function () {
    Route::get('/topup', [InvoiceController::class, 'index'])->name('invoce.index');
    Route::get('/topup/history', [InvoiceController::class, 'history'])->name('invoice.history');
    Route::post('/topup/getpayment', [InvoiceController::class,'processPayment'])->name('invoice.payment');
    Route::post('/topup/store', [InvoiceController::class, 'store'])->name('invoce.store');
    Route::get('/topup/{invoice_id}/cancel', [InvoiceController::class, 'cancel'])->name('invoice.cancel');
    Route::post('/callbcak', [InvoiceController::class, 'callback']);
    // PayPal
    Route::get('paypal/success', [InvoiceController::class,'success'])->name('paypal.success');
    Route::get('paypal/cancel', [InvoiceController::class,'cancelpaypal'])->name('paypal.cancel');
    // EndPaypal
    Route::get('/create-gift-code', [GiftCodeController::class, 'index'])->name('gift.index');
    Route::post('/create-gift-code', [GiftCodeController::class, 'createGiftCode'])->name('gift.store');
    Route::get('/redeem-gift-code', [GiftCodeController::class, 'redeem'])->name('redeem.index');
    Route::post('/redeem-gift-code', [GiftCodeController::class, 'redeemGiftCode'])->name('redeem.store');
    Route::get('/get-user-balance', [ProfileController::class, 'getUserBalance'])->name('get.user.balance');
    Route::get('/get-user-notif', [ProfileController::class, 'getUserNotif'])->name('get.user.notif');
    Route::get('/get-tunnel-settings', [ProfileController::class, 'getTunnelSettings'])->name('get.tunnel.setting');
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/settings/profile/apikey', [ProfileController::class, 'generateApiKey'])->name('profile.apikey');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Servers
    Route::get('servers/{category:slug}', [ServerController::class, 'index'])->name('servers.index');
    Route::get('servers/{category:slug}/{server:slug}/create', [ServerController::class, 'create'])->name('servers.create');
    Route::post('servers/{category:slug}/{server:slug}', [ServerController::class, 'store'])->name('servers.store');
    // Accounts
    Route::get('accounts/{category:slug}', [AccountsController::class, 'index'])->name('accounts.index');
    Route::get('accounts/{category:slug}/{username}', [AccountsController::class, 'show'])->name('accounts.show');
    Route::delete('accounts/{category:slug}/{username}', [AccountsController::class, 'destroy'])->name('accounts.destroy');
    // Accounts Update
    Route::post('accounts/{category:slug}/{username}/update', [AccountsController::class, 'update'])->name('accounts.update')->middleware('request.limit');
    // License
    Route::resource('register/license', LicenseController::class);
    //Log
    Route::get('/notifications/read', [NotificationController::class, 'markAllAsRead'])->name('notifications.read');

});

require __DIR__.'/auth.php';
