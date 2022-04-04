<?php

namespace App\Http\Controllers;

use App\DB\GoogleRepo;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Auth\StatefulGuard;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(StatefulGuard $guard)
    {
        try {
            $user = Socialite::driver('google')->stateless()->user();

            $finduser = GoogleRepo::selectUser($user->email);
            if ($finduser) {
                GoogleRepo::updateUserGoogle($finduser, $user->id);
                $guard->login($finduser);
                return redirect()->intended('dashboard');
            }

            $user = [
                'name' => $user->name,
                'email' => $user->email,
                'google_id' => $user->id,
            ];
            $newUser = GoogleRepo::createUserGoogle($user);
            $guard->login($newUser);
            return redirect()->intended('dashboard');


        } catch (Exception $e) {
            alert()->error("مشکلی وجود دارد لطفا بعدا دوباره تلاش کنید !", 'مشکلی پش آمده !')->persistent("باشه");
            return redirect(route('home'))->with("errorClint", true);
        }
    }
}
