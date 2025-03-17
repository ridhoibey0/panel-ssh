<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        $user = Auth::user();
        Log::where('user_id', $user->id)->where('status', 'unread')->update(['status' => 'read']);

        return redirect()->back()->with('status', 'All notifications have been marked as read.');
    }
}
