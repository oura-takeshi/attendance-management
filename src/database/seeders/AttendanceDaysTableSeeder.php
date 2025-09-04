<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceDaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = Carbon::today();

        $start_date = $today->copy()->subMonth()->startOfMonth();

        $user_ids = DB::table('users')->orderBy('id', 'asc')->pluck('id');

        foreach ($user_ids as $user_id) {
            for ($date = $start_date->copy(); $date->lte($today); $date->addDay()) {
                DB::table('attendance_days')->insert([
                    'user_id' => $user_id,
                    'date' => $date,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
