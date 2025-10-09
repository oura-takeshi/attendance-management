<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 1;
        $work_start_time = '09:00:00';
        $work_end_time = '17:00:00';
        $first_break_start_time = '11:00:00';
        $first_break_end_time = '12:00:00';
        $second_break_start_time = '14:00:00';
        $second_break_end_time = '15:00:00';

        $today = Carbon::today();
        $one_day_ago = $today->copy()->subDay()->toDateString();
        $two_days_ago = $today->copy()->subDays(2)->toDateString();
        $three_days_ago = $today->copy()->subDays(3)->toDateString();
        $one_month_ago = $today->copy()->subMonth()->toDateString();
        $dates = [$one_day_ago, $two_days_ago, $three_days_ago, $one_month_ago];

        $user_days = DB::table('attendance_days')->where('user_id', $user_id)->whereIn('date', $dates)->orderBy('date', 'desc')->get();

        foreach ($user_days as $user_day) {
            $work_time_id = DB::table('work_times')->insertGetId([
                    'attendance_day_id' => $user_day->id,
                    'start_time' => Carbon::parse("{$user_day->date} {$work_start_time}"),
                    'end_time' => Carbon::parse("{$user_day->date} {$work_end_time}"),
                    'created_at' => now(),
                    'updated_at' => now(),
            ]);

            DB::table('break_times')->insert([
                [
                    'work_time_id' => $work_time_id,
                    'start_time' => Carbon::parse("{$one_day_ago} {$first_break_start_time}"),
                    'end_time' => Carbon::parse("{$one_day_ago} {$first_break_end_time}"),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'work_time_id' => $work_time_id,
                    'start_time' => Carbon::parse("{$one_day_ago} {$second_break_start_time}"),
                    'end_time' => Carbon::parse("{$one_day_ago} {$second_break_end_time}"),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
