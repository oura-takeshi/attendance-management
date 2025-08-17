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
                'start_time' => Carbon::now()->subDay()->subHours(8),
                'end_time' => Carbon::now()->subDay(),
            ],
            [
                'user_id' => '1',
                'start_time' => Carbon::now()->subMonth()->subHours(8),
                'end_time' => Carbon::now()->subMonth(),
            ],
        ]);
        DB::table('break_times')->insert([
            [
                'work_time_id' => '1',
                'start_time' => Carbon::now()->subDay()->subHours(5),
                'end_time' => Carbon::now()->subDay()->subHours(4),
                'created_at' => Carbon::now()->subDay()->subHours(5),
                'updated_at' => Carbon::now()->subDay()->subHours(4),
            ],
            [
                'work_time_id' => '1',
                'start_time' => Carbon::now()->subDay()->subHours(2)->subMinutes(30),
                'end_time' => Carbon::now()->subDay()->subHours(2),
                'created_at' => Carbon::now()->subDay()->subHours(2)->subMinutes(30),
                'updated_at' => Carbon::now()->subDay()->subHours(2),
            ],
            [
                'work_time_id' => '2',
                'start_time' => Carbon::now()->subMonth()->subHours(5),
                'end_time' => Carbon::now()->subMonth()->subHours(4),
                'created_at' => Carbon::now()->subMonth()->subHours(5),
                'updated_at' => Carbon::now()->subMonth()->subHours(4),
            ],
            [
                'work_time_id' => '2',
                'start_time' => Carbon::now()->subMonth()->subHours(2)->subMinutes(30),
                'end_time' => Carbon::now()->subMonth()->subHours(2),
                'created_at' => Carbon::now()->subMonth()->subHours(2)->subMinutes(30),
                'updated_at' => Carbon::now()->subMonth()->subHours(2),
            ],
        ]);
    }
}
