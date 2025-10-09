<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WorkTimeRequestsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = 1;

        $work_start_time = '10:00:00';
        $work_end_time = '18:00:00';
        $first_break_start_time = '12:00:00';
        $first_break_end_time = '13:00:00';
        $second_break_start_time = '14:00:00';
        $second_break_end_time = '14:30:00';
        $third_break_start_time = '16:00:00';
        $third_break_end_time = '16:30:00';


        $today = Carbon::today();
        $one_day_ago = $today->copy()->subDay()->toDateString();

        $user_day = DB::table('attendance_days')->where('user_id', $user_id)->where('date', $one_day_ago)->first();

        $work_time_request_id = DB::table('work_time_requests')->insertGetId([
            'attendance_day_id' => $user_day->id,
            'start_time' => Carbon::parse("{$one_day_ago} {$work_start_time}"),
            'end_time' => Carbon::parse("{$one_day_ago} {$work_end_time}"),
            'reason' => '電車遅延のため',
            'approval' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('break_time_requests')->insert([
            [
                'work_time_request_id' => $work_time_request_id,
                'start_time' => Carbon::parse("{$user_day->date} {$first_break_start_time}"),
                'end_time' => Carbon::parse("{$user_day->date} {$first_break_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_time_request_id' => $work_time_request_id,
                'start_time' => Carbon::parse("{$user_day->date} {$second_break_start_time}"),
                'end_time' => Carbon::parse("{$user_day->date} {$second_break_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'work_time_request_id' => $work_time_request_id,
                'start_time' => Carbon::parse("{$user_day->date} {$third_break_start_time}"),
                'end_time' => Carbon::parse("{$user_day->date} {$third_break_end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
