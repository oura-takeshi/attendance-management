<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WorkTime;
use App\Models\BreakTime;
use Carbon\Carbon;

class UserController extends Controller
{
    public function attendance(){
        $user = Auth::user();

        $now = Carbon::now();

        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $date = $now->format('w');
        $day_of_week = $week[$date];

        $today = Carbon::today();
        $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        return view('user.attendance', compact('user', 'now', 'day_of_week', 'exist_work_time'));
    }

    public function workCreate(Request $request){
        switch (Auth::user()->status){
            case '1':
                User::find($request->user_id)->update(['status' => '2']);

                WorkTime::create([
                    'user_id' => Auth::id(),
                    'start_time' => Carbon::now(),
                ]);
            break;
            case '2':
                User::find($request->user_id)->update(['status' => '1']);

                $today = Carbon::today();
                $work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();
                $work_time->update(['end_time' => Carbon::now()]);
            break;
        }
        return redirect('/attendance');
    }

    public function breakCreate(Request $request)
    {
        $today = Carbon::today();
        $work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        switch (Auth::user()->status) {
            case '2':
                User::find($request->user_id)->update(['status' => '3']);

                BreakTime::create([
                    'work_time_id' => $work_time->id,
                    'start_time' => Carbon::now(),
                ]);
                break;
            case '3':
                User::find($request->user_id)->update(['status' => '2']);

                $today = Carbon::today();
                $latest_break_time = BreakTime::where('work_time_id', $work_time->id)->whereDate('start_time', $today)->latest()->first();
                $latest_break_time->update(['end_time' => Carbon::now()]);
                break;
        }
        return redirect('/attendance');
    }
}

