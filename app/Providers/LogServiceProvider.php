<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
class LogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
   public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $unreadLogsCount = Log::where('user_id', Auth::id())->where('status', 'unread')->count();
                $logs = Log::where('user_id', Auth::id())->where('status', 'unread')->latest()->take(4)->get();
                $view->with('unreadLogsCount', $unreadLogsCount);
                $view->with('logs', $logs);
            }
        });
    }


}
