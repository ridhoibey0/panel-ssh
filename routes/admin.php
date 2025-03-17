<?php

use App\Http\Controllers\Admin\ServerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'role:admin'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::get('admin/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('admin/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('admin/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Category
        Route::get('servers/create', [ServerController::class, 'create'])->name('servers.create');
        Route::post('servers/store', [ServerController::class, 'store'])->name('servers.store');
        Route::delete('servers/{serverId}', [ServerController::class, 'destroy'])->name('servers.destroy');
        Route::get('servers/{category:slug}', [ServerController::class, 'index'])->name('servers.index');
    });
});

require __DIR__.'/auth.php';
