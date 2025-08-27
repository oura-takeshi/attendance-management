<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MultiAuthTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            [
                'email' => 'admin@example.com',
                'password' => Hash::make('admin1234'),
            ],
        ]);
        DB::table('users')->insert([
            [
                'name' => 'hoge',
                'email' => 'hoge@example.com',
                'password' => Hash::make('hoge1234'),
            ],
            [
                'name' => 'fuga',
                'email' => 'fuga@example.com',
                'password' => Hash::make('fuga1234'),
            ],
        ]);
    }
}
