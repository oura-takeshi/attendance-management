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
        $start_time = '09:00:00';
        $end_time = '17:00:00';

        $user_id = 1;

        $today = Carbon::today();

        $one_day_ago = $today->copy()->subDay();
        $two_days_ago = $today->copy()->subDays(2);
        $three_days_ago = $today->copy()->subDays(3);
        $one_month_ago = $today->copy()->subMonth();
        $dates = [$one_day_ago, $two_days_ago, $three_days_ago, $one_month_ago];

        $user_days = DB::table('attendance_days')
        ->where('user_id', $user_id)
        ->whereIn('date', $dates)
        ->get();

        foreach ($user_days as $user_day) {
            DB::table('work_times')->insert([
                'attendance_day_id' => $user_day->id,
                'start_time' => Carbon::parse("{$user_day->date} {$start_time}"),
                'end_time' => Carbon::parse("{$user_day->date} {$end_time}"),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
