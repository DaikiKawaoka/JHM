<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => 'Test１銀行説明会',
            'schedule_date' => '2021-11-6'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => 'Test２会社説明会',
            'schedule_date' => '2021-11-6'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => 'Test１銀行面接',
            'schedule_date' => '2021-11-26'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 2,
            'content' => 'Test３会社説明会',
            'schedule_date' => '2021-11-3'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 2,
            'content' => 'Test４会社説明会',
            'schedule_date' => '2021-11-15'
        ]);
    }
}
