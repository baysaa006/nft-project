<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function detailUser()
    {
        $userId = Auth::user()->id;

        $user = User::find($userId);
        $user->avatar = Storage::url($user->avatar);

        $wallets = UserWallet::where('user_id', $userId)->get();
        if ($wallets) {
            $user->wallets = $wallets;
        }
        return $this->resp(['user' => $user]);
    }

    public function changeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required'
        ], [
            'avatar.required' => 'File-аа оруулна уу'
        ]);

        $path = date('Y/m') . "/avatar";
        $image = $request->file('avatar')->store($path);

        $userId = Auth::user()->id;

        $user = User::find($userId);
        $user->avatar = $image;
        $user->update();

        return $this->suc([]);
    }

    public function connectWallet(Request $request)
    {
        $request->validate([
            'wallet' => 'required'
        ], [
            'wallet.required' => 'wallet хаягаа оруулна уу'
        ]);

        $wallet = $request->input('wallet');
        $userId = Auth::user()->id;

        $exist = UserWallet::where('user_id', $userId)->where('wallet_address', $wallet)->first();

        if ($exist) {
            return $this->duplicated(['message' => 'Холбогдсон хэтэвч байна']);
        }

        $model = new UserWallet();
        $model->wallet_address = $wallet;
        $model->user_id = $userId;
        $model->save();

        return $this->suc([]);
    }

    public function removeWallet(Request $request)
    {
        $request->validate([
            'wallet' => 'required'
        ], [
            'wallet.required' => 'wallet хаягаа оруулна уу'
        ]);

        $wallet = $request->input('wallet');
        $userId = Auth::user()->id;

        $exist = UserWallet::where('user_id', $userId)->where('wallet_address', $wallet)->first();

        if (!$exist) {
            return $this->not(['message'=>'холбогдоогүй wallet байна']);
        }

        $exist->delete();

        return $this->suc(['message' => 'Холболтыг салгасан']);
    }
}
