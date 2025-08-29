<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceDay;
use App\Models\BreakTime;
use App\Models\WorkTime;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function attendance()
    {
        $today = Carbon::today();
        $user_attendance_days = AttendanceDay::where('user_id', Auth::id())->get();
        $attendance_today = $user_attendance_days->where('date', $today)->first();
        $latest_attendance_day =  $user_attendance_days->sortByDesc('date')->first();

        if ($attendance_today) {
            if ($latest_attendance_day) {
                $start_date = Carbon::parse($latest_attendance_day->date)->addDay();
            } else {
                $start_date = $today;
            }
            for ($date = $start_date->copy(); $date->lte($today); $date->addDay()) {
                AttendanceDay::create([
                    'user_id' => Auth::id(),
                    'date' => $date->toDateString(),
                ]);
            }
        }

        $now = Carbon::now();
        $date = $now->format('w');
        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $day_of_week = $week[$date];

        $attendance_today->load('workTime');
        $work_time = $attendance_today->workTime;
        if ($work_time) {
            $work_end_time = $work_time->end_time;
            $work_time->load('breakTimes');
            $break_times = $work_time->breakTimes;
            if ($break_times->isNotEmpty()) {
                $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
                $break_end_time = $break_time->end_time;
            } else {
                $break_time = null;
            }
        }

        if (!$work_time) {
            $status = 1;
        } elseif (!$work_end_time && (!$break_time || $break_end_time)) {
            $status = 2;
        } elseif (!$work_end_time && !$break_end_time) {
            $status = 3;
        } else {
            $status = 4;
        }

        return view('user.attendance', compact('now', 'day_of_week', 'status'));
    }

    public function workCreate()
    {
        $today = Carbon::today();
        $attendance_today = AttendanceDay::where('user_id', Auth::id())->where('date', $today)->first();

        $attendance_today->load('workTime');
        $work_time = $attendance_today->workTime;
        if ($work_time) {
            $work_end_time = $work_time->end_time;
            $work_time->load('breakTimes');
            $break_times = $work_time->breakTimes;
            if ($break_times->isNotEmpty()) {
                $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
                $break_end_time = $break_time->end_time;
            } else {
                $break_time = null;
            }
        }

        if (!$work_time) {
            WorkTime::create([
                'attendance_day_id' => $attendance_today->id,
                'start_time' => Carbon::now(),
            ]);
        } elseif (!$work_end_time && (!$break_times || $break_end_time)) {
            $work_time->update(['end_time' => Carbon::now()]);
        } elseif (!$work_end_time && !$break_end_time) {
            return redirect('/attendance');
        } else {
            return redirect('/attendance');
        }

        return redirect('/attendance');
    }

    public function breakCreate()
    {
        $today = Carbon::today();
        $attendance_today = AttendanceDay::where('user_id', Auth::id())->where('date', $today)->first();

        $attendance_today->load('workTime');
        $work_time = $attendance_today->workTime;
        if ($work_time) {
            $work_end_time = $work_time->end_time;
            $work_time->load('breakTimes');
            $break_times = $work_time->breakTimes;
            if ($break_times->isNotEmpty()) {
                $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
                $break_end_time = $break_time->end_time;
            } else {
                $break_time = null;
            }
        }

        if (!$work_time) {
            return redirect('/attendance');
        } elseif (!$work_end_time && (!$break_time || $break_end_time)) {
            BreakTime::create([
                'work_time_id' => $work_time->id,
                'start_time' => Carbon::now(),
            ]);
        } elseif (!$work_end_time && !$break_end_time) {
            $break_time->update(['end_time' => Carbon::now()]);
        } else {
            return redirect('/attendance');
        }

        return redirect('/attendance');
    }

    public function list($year = null, $month = null)
    {
        if ($year && $month) {
            $input_date = Carbon::create($year, $month);
            $current_year = $input_date->format('Y');
            $current_month = $input_date->format('m');
        } elseif ($year) {
            $input_date = Carbon::create($year, 1);
            $current_year = $input_date->format('Y');
            $current_month = $input_date->format('m');
        } else {
            $now = Carbon::now();
            $current_year = $now->format('Y');
            $current_month = $now->format('m');
        }

        $date = Carbon::create($current_year, $current_month);
        $prev_year = $date->copy()->subMonth()->format('Y');
        $prev_month = $date->copy()->subMonth()->format('m');
        $next_year = $date->copy()->addMonth()->format('Y');
        $next_month = $date->copy()->addMonth()->format('m');

        $days_in_month = $date->daysInMonth;
        $dates = [];
        for ($day = 1; $day <= $days_in_month; $day++) {
            $date = Carbon::create($current_year, $current_month, $day);

            $week = ['日', '月', '火', '水', '木', '金', '土'];
            $day_of_week = $week[$date->format('w')];

            $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $date)->first();
            if ($exist_work_time) {
                $work_start_time = $exist_work_time->start_time;
                $work_end_time = $exist_work_time->end_time;
                $exist_break_times = $exist_work_time->breakTimes;

                if ($exist_break_times) {
                    $total_break_time_minutes = 0;
                    foreach ($exist_break_times as $break_time) {
                        $break_start_time = $break_time->start_time;
                        $break_end_time = $break_time->end_time;

                        if ($break_end_time) {
                            $total_break_time_minutes += $break_end_time->diffInMinutes($break_start_time);
                        } else {
                            $total_break_time_minutes = 0;
                        }
                    }
                } else {
                    $total_break_time_minutes = 0;
                }
                $total_break_time = Carbon::now()->setTime(0, 0)->addMinutes($total_break_time_minutes);

                if ($work_end_time) {
                    $total_work_time_minutes = $work_end_time->diffInMinutes($work_start_time);
                    $work_time_id = $exist_work_time->id;
                } else {
                    $total_work_time_minutes = Carbon::now()->diffInMinutes($work_start_time);
                    $work_time_id = null;
                }

                $actual_work_time_minutes = $total_work_time_minutes - $total_break_time_minutes;
                $actual_work_time = Carbon::now()->setTime(0, 0)->addMinutes($actual_work_time_minutes);
            } else {
                $work_start_time = null;
                $work_end_time = null;
                $total_break_time = null;
                $actual_work_time = null;
                $work_time_id = null;
            }

            $dates[] = [
                'date' => $date,
                'day_of_week' => $day_of_week,
                'work_start_time' => $work_start_time,
                'work_end_time' => $work_end_time,
                'total_break_time' => $total_break_time,
                'actual_work_time' => $actual_work_time,
                'work_time_id' => $work_time_id
            ];
        }
        return view('user.list', compact('current_year', 'current_month', 'prev_year', 'prev_month', 'next_year', 'next_month', 'dates'));
    }

    public function detail($work_time_id)
    {
        return view('user.detail');
    }
}
