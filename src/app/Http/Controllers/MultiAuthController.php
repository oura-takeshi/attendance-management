<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class MultiAuthController extends Controller
{
    public function userStore(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => '1'
        ]);
        Auth::login($user);
        return redirect('/attendance');
    }

    public function userLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/attendance');
        } else {
            return redirect('/login')->with('message', 'ログイン情報が登録されていません');
        }
    }
}
