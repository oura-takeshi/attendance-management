<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                return redirect('/attendance');
            } else {
                $exist_break_time = BreakTime::where('work_time_id', $exist_work_time->id)->whereDate('start_time', $today)->latest()->first();

                if ($exist_break_time) {
                    $exist_break_end_time = $exist_break_time->end_time;

                    if (!$exist_break_end_time) {
                        return redirect('/attendance');
                    }
                }
                $exist_work_time->update(['end_time' => Carbon::now()]);
            }
        } else {
            WorkTime::create([
                'user_id' => Auth::id(),
                'start_time' => Carbon::now(),
            ]);
        }

        return redirect('/attendance');
    }

    public function breakCreate()
    {
        $today = Carbon::today();
        $exist_work_time = WorkTime::where('user_id', Auth::id())->whereDate('start_time', $today)->first();

        if (!$exist_work_time) {
            return redirect('/attendance');
        } else {
            $exist_work_end_time = $exist_work_time->end_time;

            if ($exist_work_end_time) {
                return redirect('/attendance');
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

        return redirect('/attendance');
    }

    public function list(Request $request, $year = null, $month = null)
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
                } else {
                    $total_work_time_minutes = Carbon::now()->diffInMinutes($work_start_time);
                }
                $actual_work_time_minutes = $total_work_time_minutes - $total_break_time_minutes;
                $actual_work_time = Carbon::now()->setTime(0, 0)->addMinutes($actual_work_time_minutes);

            } else {
                $work_start_time = null;
                $work_end_time = null;
                $total_break_time = null;
                $actual_work_time = null;
            }

            $dates[] = [
                'date' => $date,
                'day_of_week' => $day_of_week,
                'work_start_time' => $work_start_time,
                'work_end_time' => $work_end_time,
                'total_break_time' => $total_break_time,
                'actual_work_time' => $actual_work_time,
            ];
        }
        return view('user.list', compact('current_year', 'current_month', 'prev_year', 'prev_month', 'next_year', 'next_month', 'dates'));
    }
}
