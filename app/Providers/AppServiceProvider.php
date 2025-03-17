<?php

namespace App\Providers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Server;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.member', function($view){
            $categories = Category::all();
            $servers = Server::all();
            $roles = Role::where('name', '!=', 'admin')->get();
            $accounts = Account::where('user_id', Auth()->user()->id)->where('tipe', '!=', 3)->get();
            $view->with(compact('categories', 'servers', 'roles', 'accounts'));
        });
        View::composer('layouts.master', function($view){
            $categories = Category::all();
            $servers = Server::all();
            $roles = Role::where('name', '!=', 'admin')->get();
            $accounts = Account::where('user_id', Auth()->user()->id)->where('tipe', '!=', 3)->get();
            $view->with(compact('categories', 'servers', 'roles', 'accounts'));
        });
    }
}
