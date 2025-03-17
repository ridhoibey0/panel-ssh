<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\Account;
use App\Models\Log;
use App\Models\AccountTrial;

class ProfileController extends Controller
{
    public function getUserBalance(Request $request)
    {
        if ($request->ajax()) {
            $balance = number_format(auth()->user()->balance);
            return response()->json(['balance' => $balance]);
        }
    }
    
    public function getUserNotif(Request $request)
    {
        if ($request->ajax()) {
            $count = Log::where('user_id', Auth::id())->where('status', 'unread')->count();
            
            $logs = Log::where('user_id', Auth::id())->where('status', 'unread')->latest()->take(4)->get();
    
            return response()->json([
                'count' => $count,
                'logs' => $logs
            ]);
        }
    }


    
    public function getTunnelSettings()
    {
        $timelimit = getTunnelSettings("timelimit");
        $trialData = AccountTrial::where('user_id', Auth()->user()->id)->first();
        $trialLimit = $trialData ? $trialData->trial_limit : 0;
        $limit = getTunnelSettings("limit");

        $settings = [
            'timelimit' => $timelimit,
            'trial_limit' => $trialLimit,
            'limit' => $limit
        ];

        return response()->json($settings);
    }
    
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        if($request->input('email') && $request->input('email') !== auth()->user()->email) {
            return Redirect::route('profile.edit')->with('error', 'Ops, Dont Do Scams, Emails Cant Be Replaced.');
        }
        if ($request->filled('password')) {
            $request->user()->password = Hash::make($request->password);
        }
        $request->user()->name = $request->input('name');
        $request->user()->phone = $request->input('phone');
        $request->user()->save();
    
        return Redirect::route('profile.edit')->with('status', 'Update User Successfully.');
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function generateApiKey(Request $request)
    {
        $user = Auth::user();

        $user->api_key = sha1(Str::random(40));
        $user->save();

        return response()->json(["success" => ["api_key" => $user->api_key]]);
    }
}
