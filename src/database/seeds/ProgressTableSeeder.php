<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $actions = ['会社説明会','一次面接','最終面接'];
        $action_dates = ['2021-01-01','2021-02-02','2021-03-03'];
        $states = ['◯','◯','×'];
        for ($i = 1; $i<=3; $i++) {
            DB::table('progress')->insert([
                'user_id' => $i+1, //id1は先生だからプラスしている
                'entry_id' => $i,
                'action' => $actions[$i-1],
                'state' => $states[$i-1],
                'action_date' => $action_dates[$i-1],
            ]);
        }
    }
}
