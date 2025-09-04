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

        if (isset($user_attendance_days) && $user_attendance_days->isNotEmpty()) {
            $attendance_today = $user_attendance_days->where('date', $today)->first();
            if (!$attendance_today) {
                $latest_attendance_day = $user_attendance_days->sortByDesc('date')->first();
                $start_date = $latest_attendance_day->date->copy()->addDay();
                for ($date = $start_date->copy(); $date->lte($today); $date->addDay()) {
                    AttendanceDay::create([
                        'user_id' => Auth::id(),
                        'date' => $date,
                    ]);
                }
            }
        } else {
            $attendance_today = AttendanceDay::create([
                'user_id' => Auth::id(),
                'date' => $today,
            ]);
        }

        $now = Carbon::now();
        $date = $now->format('w');
        $week = ['日', '月', '火', '水', '木', '金', '土'];
        $day_of_week = $week[$date];

        if ($attendance_today && $attendance_today->workTime) {
            $work_time = $attendance_today->workTime;
        } else {
            $work_time = null;
        }
        $work_end_time = null;
        $break_time = null;
        $break_end_time = null;

        if ($work_time) {
            $work_end_time = $work_time->end_time;
            if (isset($work_time->breakTimes) && $work_time->breakTimes->isNotEmpty()) {
                $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
                if ($break_time) {
                    $break_end_time = $break_time->end_time;
                }
            }
        }

        if (!$work_time) {
            $status = 1;
        } elseif (!$work_end_time && (!$break_time || $break_end_time)) {
            $status = 2;
        } elseif (!$work_end_time && ($break_time && !$break_end_time)) {
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

        if (!$attendance_today) {
            $attendance_today = AttendanceDay::create([
                'user_id' => Auth::id(),
                'date' => $today,
            ]);
        }

        if ($attendance_today && $attendance_today->workTime) {
            $work_time = $attendance_today->workTime;
        } else {
            $work_time = null;
        }
        $work_end_time = null;
        $break_time = null;
        $break_end_time = null;

        if ($work_time) {
            $work_end_time = $work_time->end_time;
            if (isset($work_time->breakTimes) && $work_time->breakTimes->isNotEmpty()) {
                $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
                if ($break_time) {
                    $break_end_time = $break_time->end_time;
                }
            }
        }

        if (!$work_time) {
            WorkTime::create([
                'attendance_day_id' => $attendance_today->id,
                'start_time' => Carbon::now(),
            ]);
        } elseif (!$work_end_time && (!$break_time || $break_end_time)) {
            $work_time->update(['end_time' => Carbon::now()]);
        } elseif (!$work_end_time && ($break_time && !$break_end_time)) {
            return redirect('/attendance');
        }

        return redirect('/attendance');
    }

    public function breakCreate()
    {
        $today = Carbon::today();
        $attendance_today = AttendanceDay::where('user_id', Auth::id())->where('date', $today)->first();

        if (!$attendance_today) {
            $attendance_today = AttendanceDay::create([
                'user_id' => Auth::id(),
                'date' => $today,
            ]);
        }

        if ($attendance_today && $attendance_today->workTime) {
            $work_time = $attendance_today->workTime;
        } else {
            $work_time = null;
        }

        if (!$work_time) {
            return redirect('/attendance');
        }

        $work_end_time = null;
        $break_time = null;
        $break_end_time = null;

        if (isset($work_time->breakTimes) && $work_time->breakTimes->isNotEmpty()) {
            $break_time = $work_time->breakTimes->sortByDesc('start_time')->first();
            if ($break_time) {
                $break_end_time = $break_time->end_time;
            }
        }

        if (!$work_end_time && (!$break_time || $break_end_time)) {
            BreakTime::create([
                'work_time_id' => $work_time->id,
                'start_time' => Carbon::now(),
            ]);
        } elseif (!$work_end_time && ($break_time && !$break_end_time)) {
            $break_time->update(['end_time' => Carbon::now()]);
        }

        return redirect('/attendance');
    }

    public function list($year = null, $month = null)
    {
        if ($year && $month) {
            $input_date = Carbon::create($year, $month, 1);
        } elseif ($year) {
            $input_date = Carbon::create($year, 1, 1);
        } else {
            $input_date = Carbon::now()->startOfMonth();
        }
        $current_year = $input_date->format('Y');
        $current_month = $input_date->format('m');

        $first_day = Carbon::create($current_year, $current_month, 1);
        $prev_year = $first_day->copy()->subMonth()->format('Y');
        $prev_month = $first_day->copy()->subMonth()->format('m');
        $next_year = $first_day->copy()->addMonth()->format('Y');
        $next_month = $first_day->copy()->addMonth()->format('m');

        $dates = [];
        $start_date = $first_day;
        $end_date = $first_day->copy()->endOfMonth();
        $week = ['日', '月', '火', '水', '木', '金', '土'];

        for ($day = $start_date->copy(); $day->lte($end_date); $day->addDay()) {
            $date = $day->copy();
            $day_of_week = $week[$date->format('w')];

            $attendance_day = AttendanceDay::where('user_id', Auth::id())->where('date', $date)->first();

            if ($attendance_day && $attendance_day->workTime) {
                $work_time = $attendance_day->workTime;
            } else {
                $work_time = null;
            }

            $work_start_time = null;
            $work_end_time = null;
            $work_time_minutes = null;
            $total_break_time_minutes = null;

            if ($work_time) {
                $work_start_time = $work_time->start_time;

                if ($work_time->end_time) {
                    $work_end_time = $work_time->end_time;
                    $work_time_minutes = $work_end_time->diffInMinutes($work_start_time);
                } else {
                    $work_time_minutes = Carbon::now()->diffInMinutes($work_start_time);
                }

                if (isset($work_time->breakTimes) && $work_time->breakTimes->isNotEmpty()) {
                    foreach ($work_time->breakTimes as $break_time) {
                        if ($break_time->end_time) {
                            $total_break_time_minutes += $break_time->end_time->diffInMinutes($break_time->start_time);
                        } else {
                            $total_break_time_minutes += Carbon::now()->diffInMinutes($break_time->start_time);
                        }
                    }
                }
            }

            $actual_work_time_formatted = null;
            if ($work_time_minutes !== null) {
                $actual_work_time_minutes = $work_time_minutes - $total_break_time_minutes;
                $hours = intdiv($actual_work_time_minutes, 60);
                $minutes = $actual_work_time_minutes % 60;
                $actual_work_time_formatted = sprintf('%d:%02d', $hours, $minutes);
            }

            $total_break_time_formatted = null;
            if ($total_break_time_minutes !== null) {
                $hours = intdiv($total_break_time_minutes, 60);
                $minutes = $total_break_time_minutes % 60;
                $total_break_time_formatted = sprintf('%d:%02d', $hours, $minutes);
            }

            $attendance_day_id = null;
            if ($attendance_day) {
                $attendance_day_id = $attendance_day->id;
            }

            $dates[] = [
                'date' => $date,
                'day_of_week' => $day_of_week,
                'work_start_time' => $work_start_time,
                'work_end_time' => $work_end_time,
                'total_break_time' => $total_break_time_formatted,
                'actual_work_time' => $actual_work_time_formatted,
                'attendance_day_id' => $attendance_day_id,
            ];
        }

        return view('user.list', compact('current_year', 'current_month', 'prev_year', 'prev_month', 'next_year', 'next_month', 'dates'));
    }

    public function detail($attendance_day_id)
    {
        if (auth('web')->check()) {

            $attendance_day = AttendanceDay::with(['user', 'workTime.breakTimes'])->where('id', $attendance_day_id)->where('user_id', Auth::id())->first();
            if (!$attendance_day) {
                return redirect('/attendance/list');
            }

            $user_name = $attendance_day->user->name;
            $date = $attendance_day->date;

            $work_start_time = '';
            $work_end_time = '';
            $break_times = [];

            if ($attendance_day->workTime) {
                if ($attendance_day->workTime->start_time) {
                    $work_start_time = $attendance_day->workTime->start_time->format('H:i');
                }

                if ($attendance_day->workTime->end_time) {
                    $work_end_time = $attendance_day->workTime->end_time->format('H:i');
                }

                if (isset($attendance_day->workTime->breakTimes) && $attendance_day->workTime->breakTimes->isNotEmpty()) {
                    foreach ($attendance_day->workTime->breakTimes as $index => $break_time) {

                        $start_time = '';
                        $end_time = '';

                        if ($break_time->start_time) {
                            $start_time = $break_time->start_time->format('H:i');
                        }

                        if ($break_time->end_time) {
                            $end_time = $break_time->end_time->format('H:i');
                        }

                        $break_times[] = [
                            'index' => $index,
                            'start_time' => $start_time,
                            'end_time' => $end_time,
                        ];
                    }
                }
            }

            $break_times[] = [
                'index' => count($break_times),
                'start_time' => '',
                'end_time' => '',
            ];

            return view('user.detail', compact('user_name', 'date', 'work_start_time', 'work_end_time', 'attendance_day', 'break_times'));
        }

        if (auth('admin')->check()) {
            return view('admin.detail');
        }
    }

    public function request()
    {
        if (auth('web')->check()) {
            return view('user.request');
        }

        if (auth('admin')->check()) {
            return view('admin.request');
        }
    }
}
