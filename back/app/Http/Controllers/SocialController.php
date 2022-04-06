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

    protected function validateProvider($provider)
    {
        if (!in_array($provider, ['facebook', 'twitter', 'google'])) {
            return response()->json(['error' => 'Уучлаарай social суваг буруу байна.'], 422);
        }
    }

    public function loginWithFacebook($provider)
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        try {

            $user = Socialite::driver($provider)->stateless()->user();

            $ourUser = User::where('fb_id', $user->id)->first();

            if ($ourUser) {
                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token" => $token]);
            } else {

                $existmail = User::where('email', $user->email)->first();

                if ($existmail) {
                    $existmail->google_id = $user->id;
                    $existmail->update();

                    $token = $existmail->createToken('auth_token')->plainTextToken;
                    return $this->suc(["access_token" => $token]);
                }

                $ourUser = User::create([
                    'nickname' => $user->name,
                    'email' => $user->email,
                    'fb_id' => $user->id,
                    'status' => 1
                ]);

                $token = $ourUser->createToken('auth_token')->plainTextToken;
                return $this->suc(["access_token" => $token]);
            }
        } catch (Exception $exception) {
            return $this->not(["message" => $exception->getMessage()]);
        }
    }
}
