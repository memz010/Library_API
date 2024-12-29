<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect () {
        return Socialite::driver('google')->redirect();
    
    }
    public function callback () {
        $user = Socialite::driver('google')->user();

        $existeduser = User::where('oauth_id',$user->id)->where('oauth_type','google')->first();

        if ($existeduser) {
            Auth::login($existeduser);
        }

        else {
            User::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
            ]);
        }

    }
}
