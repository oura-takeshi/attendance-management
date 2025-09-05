<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceDay;
use App\Models\BreakTime;
use App\Models\User;
use App\Models\WorkTime;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function attendance($year = null, $month = null, $day = null)
    {
        if ($year && $month && $day) {
            $input_date = Carbon::create($year, $month, $day);
        } elseif ($year && $month) {
            $input_date = Carbon::create($year, $month, 1);
        } elseif ($year) {
            $input_date = Carbon::create($year, 1, 1);
        } else {
            $input_date = Carbon::today();
        }
        $current_year = $input_date;
        $current_month = $input_date;
        $current_day = $input_date;

        $current_date = Carbon::create($current_year, $current_month, $current_day);
        $prev_year = $current_date->copy()->subDay()->format('Y');
        $prev_month = $current_date->copy()->subDay()->format('m');
        $prev_day = $current_date->copy()->subDay()->format('d');
        $next_year = $current_date->copy()->addDay()->format('Y');
        $next_month = $current_date->copy()->addDay()->format('m');
        $next_day = $current_date->copy()->addDay()->format('d');

        $attendance_days = AttendanceDay::with(['user', 'workTime.breakTimes'])->where('date', $current_date)->whereHas('workTime')->orderBy('user_id', 'asc')->get();

        $users = [];

        if (isset($attendance_days) && $attendance_days->isNotEmpty()) {
            foreach ($attendance_days as $attendance_day) {
                $work_time = $attendance_day->workTime;

                $work_start_time = null;
                $work_end_time = null;
                $work_time_minutes = null;
                $total_break_time_minutes = null;

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

                $users[] = [
                    'user_name' => $attendance_day->user->name,
                    'work_start_time' => $work_start_time,
                    'work_end_time' => $work_end_time,
                    'total_break_time' => $total_break_time_formatted,
                    'actual_work_time' => $actual_work_time_formatted,
                    'attendance_day_id' => $attendance_day->id,
                ];
            }
        }

        return view('admin.attendance', compact('current_year', 'current_month', 'current_day', 'prev_year', 'prev_month', 'prev_day', 'next_year', 'next_month', 'next_day', 'users'));
    }

    public function staff()
    {
        $users = User::select('id', 'name', 'email')->orderBy('id', 'asc')->get();

        return view('admin.staff', compact('users'));
    }

    public function list($user_id)
    {
        return view('admin.list');
    }

    public function approval($work_time_request_id)
    {
        return view('admin.approval');
    }
}
