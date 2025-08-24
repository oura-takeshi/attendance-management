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
        DB::table('work_times')->insert([
            [
                'user_id' => '1',
                'start_time' => Carbon::now()->subDay()->setTime(9, 0),
                'end_time' => Carbon::now()->subDay()->setTime(17, 0),
            ],
            [
                'user_id' => '1',
                'start_time' => Carbon::now()->subDays(2)->setTime(9, 0),
                'end_time' => Carbon::now()->subDays(2)->setTime(17, 0),
            ],
            [
                'user_id' => '1',
                'start_time' => Carbon::now()->subDays(3)->setTime(9, 0),
                'end_time' => Carbon::now()->subDays(3)->setTime(17, 0),
            ],
        ]);
        DB::table('break_times')->insert([
            [
                'work_time_id' => '2',
                'start_time' => Carbon::now()->subDays(2)->setTime(11, 0),
                'end_time' => Carbon::now()->subDays(2)->setTime(12, 0),
                'created_at' => Carbon::now()->subDays(2)->setTime(11, 0),
                'updated_at' => Carbon::now()->subDays(2)->setTime(12, 0),
            ],
            [
                'work_time_id' => '3',
                'start_time' => Carbon::now()->subDays(3)->setTime(11, 0),
                'end_time' => Carbon::now()->subDays(3)->setTime(12, 0),
                'created_at' => Carbon::now()->subDays(3)->setTime(11, 0),
                'updated_at' => Carbon::now()->subDays(3)->setTime(12, 0),
            ],
            [
                'work_time_id' => '3',
                'start_time' => Carbon::now()->subDays(3)->setTime(14, 0),
                'end_time' => Carbon::now()->subDays(3)->setTime(15, 0),
                'created_at' => Carbon::now()->subDays(3)->setTime(14, 0),
                'updated_at' => Carbon::now()->subDays(3)->setTime(15, 0),
            ],
        ]);
    }
}
