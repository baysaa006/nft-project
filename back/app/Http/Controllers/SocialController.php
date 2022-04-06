<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class SocialController extends Controller
{
    public function socialRedirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function loginWithFacebook($provider)
    {
        try {
    
            $user = Socialite::driver($provider)->stateless()->user();

            die(dd($user));

            $ourUser = User::where('fb_id', $user->id)->first();
     
            if($ourUser){
                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token"=>$token]);
            }else{

                $password = Hash::make('admin@123');
                $ourUser = User::create([
                    'nickname' => $user->name,
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'password' => $password
                ]);
    
                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token"=>$token]);
            }
    
        } catch (Exception $exception) {
            return $this->not(["message"=>$exception->getMessage()]);
        }
    }
}
