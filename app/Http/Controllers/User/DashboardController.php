<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;
use App\Models\Invoice;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBalance = Auth::user()->balance;
        $totalServiceActive = Account::where('user_id', Auth::id())->where('status', 'active')->count();
        $totalSpend = Account::where('user_id', Auth::id())->sum('charge');
        $totalDeposit = Invoice::where(['user_id' => Auth::id(), 'status' => 'PAID'])->sum('amount');
        return view('dashboard', compact('totalBalance', 'totalServiceActive', 'totalSpend', 'totalDeposit'));
    }
}
