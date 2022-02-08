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
            'content' => "よんやく説明会, 場所:ZOOM, 締切日2/17(木)",
            'schedule_date' => '2022-3-10'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => "DIT学内説明, 持参物:筆記用具, 締切日2/16(水)",
            'schedule_date' => '2022-3-9'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => "インフォコム西日本（学内説明会、適正検査等, 持参物:履歴書、成績証明書（今年度前期までの分で可）、筆記用具, 締切日:2/8(火)",
            'schedule_date' => '2022-2-22'
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
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => 'DITセミナー（コミセン）',
            'schedule_date' => '2021-11-17'
        ]);
        DB::table('schedules')->insert([
            'workspace_id' => 1,
            'content' => 'DIT Webセミナー',
            'schedule_date' => '2021-11-25'
        ]);
    }
}
