<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;

class UserController extends Controller
{
    public function attendance(){
        $user = Auth::user();

        $now = Carbon::now();

        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $date = $now->format('w');
        $day_of_week = $week[$date];

        return view('user.attendance', compact('user', 'now', 'day_of_week'));
    }

    public function workCreate(Request $request){
        switch (Auth::user()->status){
            case '1':
                User::find($request->user_id)->update(['status' => '2']);
            break;
            case '2':
                User::find($request->user_id)->update(['status' => '1']);
            break;
        }
        return redirect('/attendance');
    }

    public function breakCreate(Request $request)
    {
        switch (Auth::user()->status) {
            case '2':
                User::find($request->user_id)->update(['status' => '3']);
                break;
            case '3':
                User::find($request->user_id)->update(['status' => '2']);
                break;
        }
        return redirect('/attendance');
    }
}

