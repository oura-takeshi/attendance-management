<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CorrectionRequest;
use App\Models\AttendanceDay;
use App\Models\BreakTime;
use App\Models\User;
use App\Models\WorkTime;
use App\Traits\CombinesDateTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    use CombinesDateTime;

    public function attendance($year = null, $month = null, $day = null)
    {
        $today = Carbon::today();
        $user_ids = User::pluck('id');

        foreach ($user_ids as $user_id) {
            $user_attendance_days = AttendanceDay::where('user_id', $user_id)->get();

            if (isset($user_attendance_days) && $user_attendance_days->isNotEmpty()) {
                $attendance_today = $user_attendance_days->where('date', $today)->first();
                if (!$attendance_today) {
                    $latest_attendance_day = $user_attendance_days->sortByDesc('date')->first();
                    $start_date = $latest_attendance_day->date->copy()->addDay();
                    for ($date = $start_date->copy(); $date->lte($today); $date->addDay()) {
                        AttendanceDay::create([
                            'user_id' => $user_id,
                            'date' => $date,
                        ]);
                    }
                }
            } else {
                $attendance_today = AttendanceDay::create([
                    'user_id' => $user_id,
                    'date' => $today,
                ]);
            }
        }

        if ($year && $month && $day) {
            $input_date = Carbon::create($year, $month, $day);
        } elseif ($year && $month) {
            $input_date = Carbon::create($year, $month, 1);
        } elseif ($year) {
            $input_date = Carbon::create($year, 1, 1);
        } else {
            $input_date = Carbon::today();
        }

        $prev_day_date = $input_date->copy()->subDay()->format('Y/m/d');
        $next_day_date = $input_date->copy()->addDay()->format('Y/m/d');

        $attendance_days = AttendanceDay::with(['user', 'workTime.breakTimes'])->where('date', $input_date)->whereHas('workTime')->orderBy('user_id', 'asc')->get();

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

        return view('admin.attendance', compact('input_date', 'prev_day_date', 'next_day_date', 'users'));
    }

    public function staff()
    {
        $users = User::select('id', 'name', 'email')->orderBy('id', 'asc')->get();

        return view('admin.staff', compact('users'));
    }

    public function list($user_id, $year = null, $month = null)
    {
        $user = User::where('id', $user_id)->first();
        if (!$user) {
            return redirect('/admin/attendance/list');
        }

        $user_name = $user->name;

        if ($year && $month) {
            $input_date = Carbon::create($year, $month, 1);
        } elseif ($year) {
            $input_date = Carbon::create($year, 1, 1);
        } else {
            $input_date = Carbon::now()->startOfMonth();
        }

        $current_date = $input_date->copy()->format('Y/m');
        $prev_month_date = $input_date->copy()->subMonth()->format('Y/m');
        $next_month_date = $input_date->copy()->addMonth()->format('Y/m');

        $dates = [];
        $start_date = $input_date;
        $end_date = $input_date->copy()->endOfMonth();
        $week = ['日', '月', '火', '水', '木', '金', '土'];

        for ($day = $start_date->copy(); $day->lte($end_date); $day->addDay()) {
            $date = $day->copy();
            $day_of_week = $week[$date->format('w')];

            $attendance_day = AttendanceDay::where('user_id', $user->id)->where('date', $date)->first();

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

        return view('admin.list', compact('user_name', 'user_id', 'current_date', 'prev_month_date', 'next_month_date', 'dates'));
    }

    public function workUpdate(CorrectionRequest $request)
    {
        $attendance_day = AttendanceDay::find($request->attendance_day_id);
        $attendance_day->workTime()->delete();
        $new_work_time = null;

        if ($request->work_start_time) {
            $new_work_time = WorkTime::create([
                'attendance_day_id' => $attendance_day->id,
                'start_time' => $this->combineDateTime($attendance_day->date, $request->work_start_time),
                'end_time' => $this->combineDateTime($attendance_day->date, $request->work_end_time),
            ]);
        }

        if ($new_work_time && $new_work_time->start_time) {
            foreach ($request->break_time as $break_time) {
                $break_start_time = $break_time['start_time'];
                $break_end_time = $break_time['end_time'];

                if (!$break_start_time && !$break_end_time) {
                    continue;
                }

                BreakTime::create([
                    'work_time_id' => $new_work_time->id,
                    'start_time' => $this->combineDateTime($attendance_day->date, $break_start_time),
                    'end_time' => $this->combineDateTime($attendance_day->date, $break_end_time),
                ]);
            }
        }

        return redirect('/admin/attendance/list');
    }

    public function approval($work_time_request_id)
    {
        return view('admin.approval');
    }
}
