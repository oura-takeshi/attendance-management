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
                'start_time' => Carbon::now()->startOfMonth(),
                'end_time' => Carbon::now()->startOfMonth()->addHours(8),
            ],
            [
                'user_id' => '1',
                'start_time' => Carbon::now()->startOfMonth()->subMonth(),
                'end_time' => Carbon::now()->startOfMonth()->subMonth()->addHours(8),
            ],
        ]);
        DB::table('break_times')->insert([
            [
                'work_time_id' => '1',
                'start_time' => Carbon::now()->startOfMonth()->addHours(3),
                'end_time' => Carbon::now()->startOfMonth()->addHours(4),
                'created_at' => Carbon::now()->startOfMonth()->addHours(3),
                'updated_at' => Carbon::now()->startOfMonth()->addHours(4),
            ],
            [
                'work_time_id' => '1',
                'start_time' => Carbon::now()->startOfMonth()->addHours(6),
                'end_time' => Carbon::now()->startOfMonth()->addHours(6)->addMinutes(30),
                'created_at' => Carbon::now()->startOfMonth()->addHours(6),
                'updated_at' => Carbon::now()->startOfMonth()->addHours(6)->addMinutes(30),
            ],
            [
                'work_time_id' => '2',
                'start_time' => Carbon::now()->startOfMonth()->subMonth()->addHours(3),
                'end_time' => Carbon::now()->startOfMonth()->subMonth()->addHours(4),
                'created_at' => Carbon::now()->startOfMonth()->subMonth()->addHours(3),
                'updated_at' => Carbon::now()->startOfMonth()->subMonth()->addHours(4),
            ],
            [
                'work_time_id' => '2',
                'start_time' => Carbon::now()->startOfMonth()->subMonth()->subHours(6),
                'end_time' => Carbon::now()->startOfMonth()->subMonth()->subHours(6)->addMinutes(30),
                'created_at' => Carbon::now()->startOfMonth()->subMonth()->subHours(6),
                'updated_at' => Carbon::now()->startOfMonth()->subMonth()->subHours(6)->addMinutes(30),
            ],
        ]);
    }
}
