<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function adminShowLogin()
    {
        return view('admin.auth.login');
    }

    public function adminLogin(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect('/admin/attendance/list');
        } else {
            return redirect('/admin/login')->with('message', 'ログイン情報が登録されていません');
        }
    }
}
