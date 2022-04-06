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
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function getField($provider){
        if($provider == 'facebook'){
            return 'fb_id';
        }else if($provider == 'google'){
            return 'google_id';
        }
    }

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'twitter', 'google'])) {
            return response()->json(['error' => 'Уучлаарай social суваг буруу байна.'], 422);
        }
    }

    public function loginWithSocial($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {

            $field = $this->getField($provider);

            $user = Socialite::driver($provider)->stateless()->user();

            $ourUser = User::where($field, $user->id)->first();

            if ($ourUser) {
                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token" => $token]);
            } else {

                $existmail = User::where('email', $user->email)->first();

                if ($existmail) {

                    $existmail->{$field} = $user->id;    
                    $existmail->avatar = $user->avatar;
                    $existmail->update();

                    $token = $existmail->createToken('auth_token')->plainTextToken;
                    return $this->suc(["access_token" => $token]);
                }
                $arr = [
                    'nickname' => $user->name,
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'avatar'=>$user->avatar,
                    'status' => 1,
                    $field =>$user->id
                ];
                
                $ourUser = User::create($arr);

                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token" => $token]);
            }
        } catch (Exception $exception) {
            return $this->not(["message" => $exception->getMessage()]);
        }
    }
}
