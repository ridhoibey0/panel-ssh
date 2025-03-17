<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoggedIn;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
  
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
          
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)->first();
         
            if (!$finduser) {
                $finduser = User::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'google_id'=> $user->id,
                        'password' => bcrypt('b90b1aee283c50cc47a9a6463d0d331fbb52ac7f'),
                        'avatar' => $user->avatar . '?sz=100'
                    ]
                );
                $finduser->assignRole('customer');
            }

            Auth::login($finduser);

            return redirect()->intended('dashboard');
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}
