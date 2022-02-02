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
    }
}
