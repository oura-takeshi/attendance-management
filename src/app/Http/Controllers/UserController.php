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

    public function list(Request $request, $year = null, $month = null){
        if ($year && $month) {
            $current_year = $year;
            $current_month = $month;
        } elseif ($year) {
            $current_year = $year;
            $current_month = '1';
        } else {
            $now = Carbon::now();
            $current_year = $now->year;
            $current_month = $now->month;
        }

        $date = Carbon::create($current_year, $current_month, 1);
        $prev_year = $date->copy()->subMonth()->year;
        $prev_month = $date->copy()->subMonth()->month;
        $next_year = $date->copy()->addMonth()->year;
        $next_month = $date->copy()->addMonth()->month;

        $days_in_month = $date->daysInMonth;
        $dates = [];
        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = Carbon::create($current_year, $current_month, $day);

            $week = ['日', '月', '火', '水', '木', '金', '土'];
            $day_of_week = $week[$date->format('w')];

            $dates[] = ['date' => $date, 'day_of_week' => $day_of_week];
        }
        return view('user.list', compact('current_year', 'current_month', 'prev_year', 'prev_month', 'next_year', 'next_month', 'dates'));
    }
}

