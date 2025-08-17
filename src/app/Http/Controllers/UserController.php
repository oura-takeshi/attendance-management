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
    public function attendance()
    {
        $now = Carbon::now();
        $date = $now->format('w');
        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $day_of_week = $week[$date];

        $today = Carbon::today();
        $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        if (!$exist_work_time) {
            $exist_work_end_time = null;
            $exist_break_time = null;
            $exist_break_end_time = null;
        } else {
            $exist_work_end_time = $exist_work_time->end_time;

            $exist_break_time = BreakTime::where('work_time_id', $exist_work_time->id)->whereDate('start_time', $today)->latest()->first();

            if (!$exist_break_time) {
                $exist_break_end_time = null;
            } else {
                $exist_break_end_time = $exist_break_time->end_time;
            }
        }

        return view('user.attendance', compact('now', 'day_of_week', 'exist_work_time', 'exist_work_end_time', 'exist_break_time', 'exist_break_end_time'));
    }

    public function workCreate()
    {
        $today = Carbon::today();
        $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        if ($exist_work_time) {
            $exist_work_end_time = $exist_work_time->end_time;

            if ($exist_work_end_time) {
                return back();
            } else {
                $exist_work_time->update(['end_time' => Carbon::now()]);
            }
        } else {
            WorkTime::create([
                'user_id' => Auth::id(),
                'start_time' => Carbon::now(),
            ]);
        }

        return back();
    }

    public function breakCreate()
    {
        $today = Carbon::today();
        $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        if (!$exist_work_time) {
            return back();
        } else {
            $exist_work_end_time = $exist_work_time->end_time;

            if ($exist_work_end_time) {
                return back();
            }
        }

        $exist_break_time = BreakTime::where('work_time_id', $exist_work_time->id)->whereDate('start_time', $today)->latest()->first();

        if (!$exist_break_time) {
            BreakTime::create([
                'work_time_id' => $exist_work_time->id,
                'start_time' => Carbon::now(),
            ]);
        } else {
            $exist_break_end_time = $exist_break_time->end_time;

            if ($exist_break_end_time) {
                BreakTime::create([
                    'work_time_id' => $exist_work_time->id,
                    'start_time' => Carbon::now(),
                ]);
            } else {
                $exist_break_time->update(['end_time' => Carbon::now()]);
            }
        }

        return back();
    }

    public function list(Request $request, $year = null, $month = null)
    {
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
