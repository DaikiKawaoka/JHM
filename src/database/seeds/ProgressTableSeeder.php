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
        $actions = ['説明会','面接','面接','説明会'];
        $action_dates = ['2022-01-01','2022-01-15','2022-01-30','2022-02-01'];
        $states = ['合格','不合格','不合格','結果待ち'];
        for ($i = 0; $i<34; $i++) {
            for($j = 1; $j<5; $j++){
                DB::table('progress')->insert([
                    'student_id' => $i+1,
                    'entry_id' => $j+($i*4),
                    'action' => $actions[$j - 1],
                    'state' => $states[$j - 1],
                    'action_date' => $action_dates[$j -1],
                ]);
            }
        }

        $student_ids= [6,12,17];
        $actions = ['説明会','面接','最終面接'];
        $action_dates = ['2021-05-01','2022-05-15','2022-06-01'];
        $states = ['合格','合格','内々定'];
        for($i = 0; $i < 3; $i++){
            for($j = 0; $j < 3; $j++){
                DB::table('progress')->insert([
                    'student_id' => $student_ids[$i],
                    'entry_id' => 139 + $i,
                    'action' => $actions[$j],
                    'state' => $states[$j],
                    'action_date' => $action_dates[$j],
                ]);
            }
        }

        for($i = 0; $i < 3; $i++){
            DB::table('progress')->insert([
                'student_id' => 8,
                'entry_id' => 137,
                'action' => $actions[$i],
                'state' => $states[$i],
                'action_date' => $action_dates[$i],
            ]);
        }
    }
}
