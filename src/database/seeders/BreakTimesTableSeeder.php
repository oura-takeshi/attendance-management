<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BreakTimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first_start_time = '11:00:00';
        $first_end_time = '12:00:00';

        $second_start_time = '14:00:00';
        $second_end_time = '15:00:00';

        $user_id = 1;

        $today = Carbon::today();

        $one_day_ago = $today->copy()->subDay();
        $two_days_ago = $today->copy()->subDays(2);

        $first_day = DB::table('attendance_days')->where('user_id', $user_id)->where('date', $one_day_ago)->first();

        $first_work_time = DB::table('work_times')->where('attendance_day_id', $first_day->id)->first();

        DB::table('break_times')->insert([
            [
                'work_time_id' => $first_work_time->id,
                'start_time' => Carbon::parse("{$first_day->date} {$first_start_time}"),
                'end_time' => Carbon::parse("{$first_day->date} {$first_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_time_id' => $first_work_time->id,
                'start_time' => Carbon::parse("{$first_day->date} {$second_start_time}"),
                'end_time' => Carbon::parse("{$first_day->date} {$second_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $second_day = DB::table('attendance_days')->where('user_id', $user_id)->where('date', $two_days_ago)->first();

        $second_work_time = DB::table('work_times')->where('attendance_day_id', $second_day->id)->first();

        DB::table('break_times')->insert([
            [
                'work_time_id' => $second_work_time->id,
                'start_time' => Carbon::parse("{$second_day->date} {$first_start_time}"),
                'end_time' => Carbon::parse("{$second_day->date} {$first_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
