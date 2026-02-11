<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            \Log::info('Social User details:', [
                'id' => $socialUser->getId(),
                'email' => $socialUser->getEmail(),
                'name' => $socialUser->getName(),
            ]);

            $user = User::where('social_id', $socialUser->getId())
                        ->where('social_type', $provider)
                        ->first();

            if (!$user) {
                $user = User::where('email', $socialUser->getEmail())->first();

                if ($user) {
                    $user->update([
                        'social_id' => $socialUser->getId(),
                        'social_type' => $provider,
                    ]);
                    \Log::info('Existing user updated with social details:', ['user_id' => $user->id]);
                } else {
                    $user = User::create([
                        'name' => $socialUser->getName(),
                        'email' => $socialUser->getEmail(),
                        'social_id' => $socialUser->getId(),
                        'social_type' => $provider,
                        'password' => null, 
                    ]);
                    \Log::info('New user created from social login:', ['user_id' => $user->id]);
                }
            }

            Auth::login($user);
            \Log::info('User logged in:', ['user_id' => $user->id]);

            return redirect()->route('dashboard');

        } catch (Exception $e) {
            \Log::error('Socialite authentication error:', [
                'provider' => $provider,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect('/login')->with('error', 'Something went wrong during ' . $provider . ' login: ' . $e->getMessage());
        }
    }
}
