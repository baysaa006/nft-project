<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function detailUser(){
        $userId = Auth::user()->id;

        $user = User::find($userId);
        $user->avatar = Storage::url($user->avatar);
        return $this->resp(['user'=>$user]);
    }

    public function changeAvatar(Request $request){
        $request->validate([
            'avatar'=>'required'
        ], [
            'avatar.required'=>'File-аа оруулна уу'
        ]);

        $path = date('Y/m')."/avatar";
        $image = $request->file('avatar')->store($path);

        $userId = Auth::user()->id;

        $user = User::find($userId);
        $user->avatar = $image;
        $user->update();

        return $this->suc([]);
    }
}
