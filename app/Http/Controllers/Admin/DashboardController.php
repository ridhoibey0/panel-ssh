<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalance = Auth::user()->balance;
        $activeUsersByRole = User::where('status', 'active')
            ->whereHas('roles')
            ->with('roles')
            ->get()
            ->groupBy(function ($user) {
                return $user->roles->pluck('name')->first();
            })
            ->map->count();
        $totalServiceActive = Account::where('user_id', Auth::id())->where('status', 'active')->count();
        $totalTransactionToday = Account::whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->sum('charge');
        $totalTransactionThisMonth = Account::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('charge');
        $totalDepositToday = Invoice::where('status', 'PAID')
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])
            ->sum('amount');
        $totalDepositThisMonth = Invoice::where('status', 'PAID')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        $notification = Log::latest()->limit(6)->get();
        return view('dashboard-1', compact('notification', 'activeUsersByRole', 'totalTransactionThisMonth', 'totalTransactionToday', 'totalDepositToday', 'totalDepositThisMonth'));
    }
}
